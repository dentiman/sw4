<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 11.12.16
 * Time: 15:37
 */

namespace App\Command\FeedImport;

use App\Entity\Feed\MainTech;
use App\Entity\FeedImport\Basic\FeedYahooQuote;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class YahooTechCommand extends BaseFeedImportCommand
{
    public function getDefaultDefinition()
    {
        return '15 1 * * *';
    }


    public function isActive()
    {
        return false;
    }

    protected function configure()
    {
        $this
            ->setName('cron:feed:yahoo:tech')
            ->setDescription('Update');
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $YahooQuotes = $this->em->getRepository(FeedYahooQuote::class)->findAll();
        if (count($YahooQuotes)<1000) throw new \LogicException('No data to import');
        $this->truncateTable(MainTech::class);

        /** @var FeedYahooQuote $YahooQuote */
        foreach ($YahooQuotes as $YahooQuote) {

            $yahooTech = new MainTech();
            $yahooTech->setId($YahooQuote->getSymbol());
            $yahooTech->setAverageDailyVolume3Month($YahooQuote->getAverageDailyVolume3Month());
            $yahooTech->setAverageDailyVolume10Day($YahooQuote->getAverageDailyVolume10Day());
            $yahooTech->setFiftyDayAverage($YahooQuote->getFiftyDayAverage());
            $yahooTech->setFiftyDayAverageChange($YahooQuote->getFiftyDayAverage());
            $yahooTech->setFiftyDayAverageChangePercent($YahooQuote->getFiftyDayAverageChangePercent());
            $yahooTech->setTwoHundredDayAverage($YahooQuote->getTwoHundredDayAverage());
            $yahooTech->setTwoHundredDayAverageChange($YahooQuote->getTwoHundredDayAverageChange());
            $yahooTech->setTwoHundredDayAverageChangePercent($YahooQuote->getTwoHundredDayAverageChangePercent());
            $yahooTech->setFiftyTwoWeekHigh($YahooQuote->getFiftyTwoWeekHigh());
            $yahooTech->setFiftyTwoWeekHighChange($YahooQuote->getFiftyTwoWeekHighChange());
            $yahooTech->setFiftyTwoWeekHighChangePercent($YahooQuote->getFiftyTwoWeekHighChangePercent());

            $yahooTech->setFiftyTwoWeekLow($YahooQuote->getFiftyTwoWeekLow());
            $yahooTech->setFiftyTwoWeekLowChange($YahooQuote->getFiftyTwoWeekLowChange());
            $yahooTech->setFiftyTwoWeekLowChangePercent($YahooQuote->getFiftyTwoWeekLowChangePercent());
            $yahooTech->setForwardPE($YahooQuote->getForwardPE());
            $yahooTech->setMarketCap($YahooQuote->getMarketCap());
            $yahooTech->setSharesOutstanding($YahooQuote->getSharesOutstanding());
            $yahooTech->setTrailingAnnualDividendRate($YahooQuote->getTrailingAnnualDividendRate());
            $yahooTech->setTrailingAnnualDividendYield($YahooQuote->getTrailingAnnualDividendYield());
            $yahooTech->setTrailingPE($YahooQuote->getTrailingPE());

//            $output->writeln($yahooTech->getId());

            $this->em->persist($yahooTech);
        }

        $this->em->flush();

        $this->updateBaseGrid();

    }

}
