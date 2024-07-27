<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 11.12.16
 * Time: 15:37
 */

namespace App\Command\FeedImport;

use App\Entity\Feed\MainTickers;
use App\Entity\FeedImport\Basic\FeedYahooQuote;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\DataFeedApp\Api\YahooFinanceApi\YahooApiClientFactory;

class YahooQuoteCommand extends BaseFeedImportCommand
{
    public function getDefaultDefinition()
    {
        return '10 1 * * *';
    }


    protected function configure()
    {
        $this
            ->setName('cron:feed:yahoo:quote')
            ->setDescription('Update YahooQuote Entity');
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Yahoo quote downloading ...  </comment>');
        $client = YahooApiClientFactory::createApiClient();
        $this->truncateTable(FeedYahooQuote::class);

        $ticketChunk = array_chunk($this->getBaseTickers(), 100);

        foreach ($ticketChunk as $tickets) {

            $quotes = $client->getQuotes($tickets);
            foreach ($quotes as $YahooQuote) {

                if($YahooQuote->getRegularMarketChangePercent()==100 || $YahooQuote->getRegularMarketVolume() < 1) {
                    $output->writeln($YahooQuote->getSymbol());
                   $tickerToRemove =  $this->getEntityManager()->getRepository(MainTickers::class)->find($YahooQuote->getSymbol());
                   if($tickerToRemove) {
                       $this->getEntityManager()->remove($tickerToRemove);
                   }
                }

               // $output->writeln($YahooQuote->getSymbol().' '.$YahooQuote->getMarketCap());

                $this->em->persist($YahooQuote);

            }
        }

        $this->em->flush();

        $this->addMessage($this->getRowsCount(FeedYahooQuote::class,'symbol'));

    }

}
