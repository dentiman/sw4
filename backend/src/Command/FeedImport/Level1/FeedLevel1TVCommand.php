<?php

namespace App\Command\FeedImport\Level1;

use App\Command\FeedImport\BaseFeedImportCommand;
use App\Entity\Feed\MainLevel1;
use App\Entity\FeedImport\Basic\FeedBasicTickers;
use App\Entity\FeedImport\Level1\FeedLevel1TV;
use App\Entity\FeedImport\Level1\FeedLevel1Yahoo;
use GuzzleHttp\Psr7\Request;
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

class FeedLevel1TVCommand extends BaseFeedImportCommand
{
    protected static $defaultName = 'cron:feed:level1:tv';

    public function getDefaultDefinition()
    {
        return '30 16 * * *';
    }


    protected function configure()
    {
        $this
            ->setDescription('Trading view')
        ;
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable( FeedLevel1TV::class);
        $client = new Client();
        $formData = '{"filter":[{"left":"name","operation":"nempty"},{"left":"type","operation":"in_range","right":["stock","dr","fund"]},{"left":"subtype","operation":"in_range","right":["common","","etf","unit","mutual","money","reit","trust"]},{"left":"exchange","operation":"in_range","right":["AMEX","NASDAQ","NYSE"]}],"options":{"lang":"en"},"symbols":{"query":{"types":[]},"tickers":[]},"columns":["logoid","name","open","high","low","close","change","change_abs","volume"],"sort":{"sortBy":"name","sortOrder":"asc"},"range":[0,8500]}';

        $response = $client->request('POST', 'https://scanner.tradingview.com/america/scan', [
            'body' =>  $formData
        ]);
        $array =  json_decode($response->getBody(),true);

        if(!isset($array['data']) ) { throw new \Exception(json_encode($array));}

        foreach ($array['data'] as $item) {
            $level1Tv = new FeedLevel1TV();
            $level1Tv->setId($item['d'][1]);
            $level1Tv->setOp($item['d'][2]);
            $level1Tv->setHi($item['d'][3]);
            $level1Tv->setLo($item['d'][4]);
            $level1Tv->setPrice($item['d'][5]);
            $level1Tv->setChp($item['d'][6]);
            $level1Tv->setCh($item['d'][7]);
            $level1Tv->setVol($item['d'][8]);
            $this->getEntityManager()->persist($level1Tv);
        }

        $this->em->flush();
        $count = $this->getRowsCount( FeedLevel1TV::class) ;
        if($count > 7000 ){
            $this->truncateTable(MainLevel1::class);
            $this->dbQuery("REPLACE INTO `feed_main_level1` SELECT * FROM `feed_level1_tv`");
        };
        $this->addMessage($count);
        $this->updateBaseGrid();

    }
}
