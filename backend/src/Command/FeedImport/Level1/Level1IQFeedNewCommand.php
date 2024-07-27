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
use App\Entity\Feed\MainLevel1;
use App\Entity\Feed\MainPremarket;
use App\Entity\FeedImport\Level1\FeedLevel1Sources;
use App\Entity\FeedImport\Premarket\FeedPremarketIqfeed;
use Http\Promise\Promise;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpClient\CurlHttpClient;
use Clue\React\Buzz\Browser;
use Clue\React\Mq\Queue;
class Level1IQFeedNewCommand extends BaseFeedImportCommand
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
            ->setName('cron:feed:level1:feed-new')
            ->setDescription('Update level1 table from IQfeed')
           ;
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Iqfeed downloading ...  </comment>');

        if( Helper::marketTime() === Helper::MARKET_CLOSED_TIME) {
            $this->addMessage('market closed');
            return;
        }

        if( Helper::marketTime() === Helper::POST_MARKET_TIME) {
            $this->addMessage('post market time');
            return;
        }

        $isPremarket = Helper::marketTime() == Helper::PRE_MARKET_TIME;

        if($isPremarket) {
            $this->dbQuery("TRUNCATE feed_premarket_iqfeed");
            $source = 'http://168.119.129.37:9390/feeddone/level1_premarket';
        } else {
            $this->dbQuery("TRUNCATE feed_level1_sources");
            $source = 'http://168.119.129.37:9390/feeddone/level1';
        }

        $client = HttpClient::create(['timeout'=>1.5]);
        $content = $client->request('GET', $source);
        $content =  $content->getContent();

        if($isPremarket) {
            $this->updPremarket($content);
        } else {
            $this->updLevel1($content);
        }

        $this->em->flush();
        if($isPremarket) {
            $this->dbQuery("REPLACE INTO `feed_main_premarket` SELECT * FROM `feed_premarket_iqfeed`");
            $this->addMessage('prem total:'.$this->getRowsCount(FeedPremarketIqfeed::class));
            $this->addMessage(json_encode($this->getCountByLastTradeTimePremarket()));
        } else {
            $this->dbQuery("UPDATE `feed_level1_sources` SET `chp` = ROUND(chp*100,2), `bidsize` = bidsize/100, `asksize` = asksize/100");
            $this->dbQuery("REPLACE INTO `feed_main_level1` SELECT * FROM `feed_level1_sources`");
            $this->addMessage('total:'.$this->getRowsCount(FeedLevel1Sources::class));
            $this->addMessage(json_encode($this->getCountByLastTradeTime()));
        }

        $this->updateBaseGrid();
    }


    protected function getCountByLastTradeTime()
    {
        $result = $this->getRepository(MainLevel1::class)
            ->createQueryBuilder('c')
            ->select('DATE_FORMAT(c.ttime, \'%Y-%m-%d %H:%i\') as lastTradeTime, count(c.id) as count')
            ->groupBy('lastTradeTime')
            ->orderBy('count','DESC')
            ->getQuery()
            ->getResult();
        $groups = [];
        foreach ($result as $row) {
            if($row['count']*1 >= 10)
            $groups[$row['lastTradeTime']] = $row['count'];
        }
        return $groups;

    }

    protected function getCountByLastTradeTimePremarket()
    {
        $result = $this->getRepository(MainPremarket::class)
            ->createQueryBuilder('c')
            ->select('DATE_FORMAT(c.pttime, \'%Y-%m-%d %H:%i\') as lastTradeTime, count(c.id) as count')
            ->groupBy('lastTradeTime')
            ->orderBy('count','DESC')
            ->getQuery()
            ->getResult();
        $groups = [];
        foreach ($result as $row) {
            if($row['count']*1 >= 10)
                $groups[$row['lastTradeTime']] = $row['count'];
        }
        return $groups;

    }


    protected function validate($val) {
       return strlen(str_replace(' ','',$val)) > 0 ? $val : null;
    }


    protected function updLevel1($csvContent)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $csvHead = "ticket,price,op,hi,lo,chp,ch,ttime,tdate,bid,ask,bidsize,asksize,tcount,vol\n";
        $data = $serializer->decode($csvHead.$csvContent, 'csv');
        $uniqueTickers = [];

        if(count($data)< 1) return;

        foreach ($data as $item) {
            if(count($item) !== 15) {
                continue;
            }

            if(in_array($item['ticket'],$uniqueTickers)) continue;
            $uniqueTickers[] = $item['ticket'];

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
        $uniqueTickers = [];

        foreach ($data as $item) {
            if(count($item) !== 7) {
                continue;
            }

            if(in_array($item['ticker'],$uniqueTickers)) continue;
            $uniqueTickers[] = $item['ticker'];

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
