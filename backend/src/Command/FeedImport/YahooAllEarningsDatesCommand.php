<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 11.12.16
 * Time: 15:37
 */

namespace App\Command\FeedImport;

use App\Entity\FeedImport\Basic\FeedBasicEarnings;
use App\Entity\FeedImport\Basic\FeedYahooQuote;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class YahooAllEarningsDatesCommand extends BaseFeedImportCommand
{
    public function getDefaultDefinition()
    {
        return '20 18 * * 1-5';
    }


    protected function configure()
    {
        $this
            ->setName('feed:yahoo:earnings:all')
            ->setDescription('Update from yahoo quote table');
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $YahooQuotes = $this->em->getRepository(FeedYahooQuote::class)->findAll();
        if (count($YahooQuotes)<1000) throw new \LogicException('No data to import');
        $this->truncateTable(FeedBasicEarnings::class);

        /** @var FeedYahooQuote $YahooQuote */
        foreach ($YahooQuotes as $YahooQuote) {

            $feedBaseEarning = new FeedBasicEarnings();

            $feedBaseEarning->setId($YahooQuote->getSymbol());
            $feedBaseEarning->setEarn($YahooQuote->getEarningsTimestamp());
            $this->em->persist($feedBaseEarning);
        }

        $this->em->flush();
        $this->replaceInto("feed_basic_earnings", "feed_main_earnings");


    }

}
