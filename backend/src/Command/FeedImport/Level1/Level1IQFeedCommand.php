<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 11.12.16
 * Time: 15:37
 */

namespace App\Command\FeedImport\Level1;

use App\Command\FeedImport\BaseFeedImportCommand;
use App\DataFeedApp\Helper;
use App\Entity\FeedImport\Level1\FeedLevel1Sources;
use App\Entity\FeedImport\Premarket\FeedPremarketIqfeed;
use Http\Promise\Promise;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpClient\CurlHttpClient;
use Clue\React\Buzz\Browser;
use Clue\React\Mq\Queue;
class Level1IQFeedCommand extends BaseFeedImportCommand
{
    public function getDefaultDefinition()
    {
        return '* 7-16 * * 1-5';
    }


    public function isActive()
    {
        return true;
    }

    protected function configure()
    {
        $this
            ->setName('cron:feed:level1:feed')
            ->setDescription('Update level1 table from IQfeed')
           ;
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $tickerChunk = array_chunk($this->getBaseTickers(),300);
        $output->writeln("<comment>Downloading ".count($tickerChunk)." tickers Chunks  </comment>");
        $output->writeln('<comment>Iqfeed downloading ...  </comment>');
        $isPremarket = Helper::marketTime() == Helper::PRE_MARKET_TIME;
        $loop = \React\EventLoop\Factory::create();
        $browser = new Browser($loop);
        $urls = [];

        if($isPremarket) {
            $this->dbQuery("TRUNCATE feed_premarket_iqfeed");
            $source = 'http://stock-watcher.com:9390/plesk-site-preview/w.stock-watcher.com/open_get_prem.php?t=';
        } else {
            $this->dbQuery("TRUNCATE feed_level1_sources");
            $source = 'http://stock-watcher.com:9390/plesk-site-preview/w.stock-watcher.com/open_get.php?t=';
        }

        foreach ($tickerChunk as $tickers) {
            $urls[] = $source.implode(',',$tickers);
        }


        $promise = Queue::all(15, $urls, function ($url) use ($browser) {

            return $browser->get($url);
        });

        $promise->then(function (array $responses) use ( $isPremarket,$loop) {
            foreach ($responses as $response) {
                if($isPremarket) {
                    $this->updPremarket($response->getBody());
                    echo 'handled!' . PHP_EOL;
                } else{
                    $this->updLevel1($response->getBody());
                    echo 'handled!' . PHP_EOL;
                }
            }
            $loop->stop();
        });

        $loop->addTimer(50.0, function () use ($loop, $output,$promise) {
            $output->writeln('exit by timeout');
            $promise->cancel();
            $loop->stop();
            $this->addMessage('loop time out');
        });

        $loop->run();
        $output->writeln('finish');
        $this->em->flush();
        if($isPremarket) {
            $this->dbQuery("REPLACE INTO `feed_main_premarket` SELECT * FROM `feed_premarket_iqfeed`");
            $this->addMessage('Records count:'.$this->getRowsCount(FeedPremarketIqfeed::class));
        } else {
            $this->dbQuery("UPDATE `feed_level1_sources` SET `chp` = ROUND(chp*100,2), `bidsize` = bidsize/100, `asksize` = asksize/100");
            $this->dbQuery("REPLACE INTO `feed_main_level1` SELECT * FROM `feed_level1_sources`");
            $this->addMessage('Records count:'.$this->getRowsCount(FeedLevel1Sources::class));
        }
        $this->updateBaseGrid();
    }


    protected function validate($val) {
       return strlen(str_replace(' ','',$val)) > 0 ? $val : null;
    }


    protected function updLevel1($csvContent )
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $csvHead = "ticket,price,op,hi,lo,chp,ch,ttime,tdate,bid,ask,bidsize,asksize,tcount,vol\n";
        $data = $serializer->decode($csvHead.$csvContent, 'csv');

        if(count($data)< 1) return;

        foreach ($data as $item) {
            $FeedLevel1Source = new FeedLevel1Sources();
            $FeedLevel1Source->setId( $this->validate( $item['ticket']));
            $FeedLevel1Source->setPrice($this->validate($item['price']));
            $FeedLevel1Source->setOp($this->validate($item['op']));
            $FeedLevel1Source->setHi($this->validate($item['hi']));
            $FeedLevel1Source->setLo($this->validate($item['lo']));
            $FeedLevel1Source->setChp($this->validate($item['chp']));
            $FeedLevel1Source->setCh($this->validate($item['ch']));
            try {
               $d =  new \DateTime($item['tdate'].' '.$item['ttime']);
            } catch (\Exception $e) {
                $d =  null;
                echo $e->getMessage()."\r\n";
            }
            $FeedLevel1Source->setTtime( $d);
            $FeedLevel1Source->setBid($this->validate($item['bid']));
            $FeedLevel1Source->setAsk($this->validate($item['ask']));
            $FeedLevel1Source->setBidsize($this->validate($item['bidsize']));
            $FeedLevel1Source->setAsksize($this->validate($item['asksize']));
            $FeedLevel1Source->setTcount($this->validate($item['tcount']));
            $FeedLevel1Source->setVol($this->validate($item['vol']));
            $FeedLevel1Source->setSource('i1');
            $this->em->persist($FeedLevel1Source);
        }
    }

    protected function updPremarket($csvContent)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $csvHead = "ticker,pvol,ptcount,pprice,pch,tdate,ttime\n";
        $data = $serializer->decode($csvHead.$csvContent, 'csv');


        foreach ($data as $item) {
           $FeedPremarket = new FeedPremarketIqfeed();
           $FeedPremarket->setId($this->validate($item['ticker']));
           $FeedPremarket->setPvol($this->validate($item['pvol']));
           $FeedPremarket->setPtcount($this->validate($item['ptcount']));
           $FeedPremarket->setPprice($this->validate($item['pprice']));
           $FeedPremarket->setPchp($this->calculatePchp($this->validate($item['pch'])*1,$this->validate($item['pprice'])*1));
           $FeedPremarket->setPch($this->validate($item['pch']));

            try {
                $d =  new \DateTime($item['tdate'].' '.$item['ttime']);
            } catch (\Exception $e) {
                $d =  null;
            }

            $FeedPremarket->setPttime($d);

           $this->em->persist($FeedPremarket);

        }
    }

    protected function calculatePchp($pch,$pprice)
    {
        $closePrice = $pprice - $pch;
        if($closePrice == 0) { return 0;}
        return round( $pprice / $closePrice * 100 - 100,2);
    }


}
