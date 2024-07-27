<?php

namespace App\Command\FeedImport\Level1;

use App\Command\FeedImport\BaseFeedImportCommand;
use App\Entity\Feed\MainLevel1;
use App\Entity\FeedImport\Basic\FeedBasicTickers;
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

class FeedLevel1RapidApiYahooCommand extends BaseFeedImportCommand
{
    protected static $defaultName = 'feed:level1:rapidapi:yahoo';

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
       // $this->truncateTable( MainLevel1::class);
        $ticketChunk = array_chunk($this->getBaseTickers(),500);

        $client = new Client();

        foreach ($ticketChunk as $tickers) {
            $request = new Request(
                'GET',
                'https://apidojo-yahoo-finance-v1.p.rapidapi.com/market/v2/get-quotes?region=US&symbols='.implode(',',$tickers),
                [
                    'x-rapidapi-key' => '294f1ea28cmsh7e15d0539353826p1798a2jsn1588955a4406',
                    'x-rapidapi-host' => 'apidojo-yahoo-finance-v1.p.rapidapi.com'
                ]
            );

            $response = json_decode( $client->send($request)->getBody()->getContents(),true);

            foreach ( $response['quoteResponse']['result'] as $quote) {
                var_dump( count($response['quoteResponse']['result']));
            }
        }


    //    var_dump( $response['quoteResponse']['result']);

       // $this->em->flush();

    }
}
