<?php
namespace App\Command\FeedImport;

use App\Entity\Feed\MainLevel1;
use App\Entity\Feed\MainTickers;
use App\Entity\FeedImport\Basic\FeedBasicTickers;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UpdateTickersCommand extends BaseFeedImportCommand
{

    public function getDefaultDefinition()
    {
        return '01 1 * * *';
    }


    protected function configure()
    {
        $this
            ->setName('cron:feed:tickers')
            ->setDescription('Update tickers from nasdaq');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {

       if(count($this->em->getRepository(MainLevel1::class)->findAll()) < 7000) throw new \Exception('MainTickers < 7000 records');

        $ignoreTickets = ['ZTEST', 'ZVV'];

        $this->truncateTable(FeedBasicTickers::class);
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        $exchangeLabels = [
            'N' => 1,
            'A' => 3,
            'P' => 4,
            'Z' => 6
        ];

        $availableLables = ['N','A','P','Z'];

        // IMPORT NASDAQ
        //http://www.nasdaqtrader.com/dynamic/symdir/nasdaqlisted.txt
        $str =  @file_get_contents('ftp://ftp.nasdaqtrader.com/symboldirectory/nasdaqlisted.txt');
        $data = $serializer->decode($str, 'csv',['csv_delimiter'=>'|']);
        foreach ($data as $item) {

            if(
                isset($item['Symbol']) &&
                isset($item['Market Category']) &&
                isset($item['ETF']) &&
                strlen($item['ETF']) > 0 &&
                strlen($item['Market Category']) > 0 &&
                $this->ticketIsValid($item['Symbol'])
            ) {
                if (in_array($item['Symbol'],$ignoreTickets)) continue;
                if(!$this->em->getRepository(MainLevel1::class)->find($item['Symbol'])) continue;

                $feedBasicTickers = new FeedBasicTickers;
                $feedBasicTickers->setId($item['Symbol']);
                $feedBasicTickers->setName($this->validateName($item['Security Name']));
                $feedBasicTickers->setExchange(2);
                $feedBasicTickers->setEtf(
                    $item['ETF'] == 'Y' ? 1: 0
                );

                $this->em->persist($feedBasicTickers);

            }
        }

        $str =  @file_get_contents('ftp://ftp.nasdaqtrader.com/symboldirectory/otherlisted.txt');
        $data = $serializer->decode($str, 'csv',['csv_delimiter'=>'|']);
                foreach ($data as $item) {

            if(
                isset($item['ACT Symbol']) &&
                isset($item['Exchange']) &&
                isset($item['ETF']) &&
                strlen($item['ETF']) > 0 &&
                in_array($item['Exchange'], $availableLables) &&
                $this->ticketIsValid($item['ACT Symbol'])
            ) {
                if (in_array($item['ACT Symbol'],$ignoreTickets)) continue;
                if(!$this->em->getRepository(MainLevel1::class)->find($item['ACT Symbol'])) continue;

                $feedBasicTickers = new FeedBasicTickers;
                $feedBasicTickers->setId($item['ACT Symbol']);
                $feedBasicTickers->setName($this->validateName($item['Security Name']));
                $feedBasicTickers->setExchange($exchangeLabels[$item['Exchange']]);
                $feedBasicTickers->setEtf(
                    $item['ETF'] == 'Y' ? 1: 0
                );

                $this->em->persist($feedBasicTickers);
             //   $output->writeln($item['ACT Symbol']) ;
            }
        }

                $this->em->flush();

       $exchangeCountRows = $this->getRepository(FeedBasicTickers::class)
            ->createQueryBuilder('t')
            ->select("t.exchange, COUNT(t.id) as count")
            ->groupBy("t.exchange")
            ->getQuery()
            ->getResult();

        $exchangeCountArray = [];

        foreach ($exchangeCountRows  as  $item) {
          $exchangeCountArray[$item['exchange']] = $item['count'];
        }

        $this->addMessage(json_encode($exchangeCountArray));

        // TODO:: if success all tickers
        $this->truncateTable(MainTickers::class);
        $this->dbQuery("REPLACE INTO `feed_main_tickers` SELECT * FROM `feed_basic_tickers`");


        // TODO:: remove ZTEST
    }

    protected function existInLevel1(string $ticker)
    {
        $this->em->getRepository(MainTickers::class)->find($ticker);
    }


    protected function ticketIsValid($ticket)
    {
        if (strlen($ticket)> 0 && strlen($ticket) < 5 && ctype_alpha($ticket)) {
            return true;
        }

        return false;
    }


    protected function validateName($name)
    {
        $name = explode(" - ",$name);

        return str_replace([
            'Common Stock',
            'Common Shares'
        ],'',$name[0]);


    }
}
