<?php

namespace App\Command\FeedImport\Level1;

use App\Command\FeedImport\BaseFeedImportCommand;
use App\Entity\Feed\MainLevel1;
use App\Entity\FeedImport\Basic\FeedBasicTickers;
use App\Entity\FeedImport\Level1\FeedLevel1Yahoo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\DataFeedApp\Api\YahooFinanceApi\YahooApiClient;
use App\DataFeedApp\Api\YahooFinanceApi\YahooApiClientFactory;
use GuzzleHttp\Client;
use Symfony\Component\HttpClient\CurlHttpClient;

class FeedLevel1YahooCommand extends BaseFeedImportCommand
{
    protected static $defaultName = 'cron:feed:level1:yahoo';

    public function getDefaultDefinition()
    {
        return '30 16 * * *';
    }


    protected function configure()
    {
        $this
            ->setDescription('Yahoo')
        ;
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $client = YahooApiClientFactory::createApiClient();
        $this->truncateTable( FeedLevel1Yahoo::class);
        $ticketChunk = array_chunk($this->getBaseTickers(),100);

        foreach ($ticketChunk as $tickets) {

            $quotes = $client->getQuotes($tickets);
            foreach ($quotes as $YahooQuote) {

                 //   $output->writeln($YahooQuote->getSymbol());

                    $level1 = new FeedLevel1Yahoo();
                    $level1->setId($YahooQuote->getSymbol());
                    $level1->setAsk($YahooQuote->getAsk());
                    $level1->setBid($YahooQuote->getBid());
                    $level1->setAsksize($YahooQuote->getAskSize());
                    $level1->setBidsize($YahooQuote->getBidSize());
                    $level1->setPrice($YahooQuote->getRegularMarketPrice());
                    $level1->setOp($YahooQuote->getRegularMarketOpen());
                    $level1->setHi($YahooQuote->getRegularMarketDayHigh());
                    $level1->setLo($YahooQuote->getRegularMarketDayLow());
                    $level1->setChp($YahooQuote->getRegularMarketChangePercent());
                    $level1->setCh($YahooQuote->getRegularMarketChange());
                    $level1->setVol($YahooQuote->getRegularMarketVolume());
                    $level1->setTtime($YahooQuote->getRegularMarketTime());
                    $this->em->persist($level1);
            }
        }

        $this->em->flush();
        $count = $this->getRowsCount(FeedLevel1Yahoo::class) ;
        if($count > 7000 ){
            $this->truncateTable(MainLevel1::class);
            $this->dbQuery("REPLACE INTO `feed_main_level1` SELECT * FROM `feed_level1_yahoo`");
        };
        $this->addMessage($count);
        $this->updateBaseGrid();

    }
}
