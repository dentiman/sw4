<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 11.12.16
 * Time: 15:37
 */

namespace App\Command\FeedImport\Charts;

use App\Command\FeedImport\BaseFeedImportCommand;
use App\DataFeedApp\Bar\Storage\DailyBarStorage;
use App\Entity\Feed\MainTickers;
use App\Entity\FeedImport\Charts\TickerTaskDailyCharts;
use App\Entity\FeedImport\Charts\DailyHistory;
use App\Entity\FeedImport\Charts\IntradayCounter;
use App\Entity\FeedImport\Charts\TickerTaskMinuteCharts;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChartsResetCommand extends BaseFeedImportCommand
{
    public function getDefaultDefinition()
    {
        return '50 19 * * 1-5';
    }


    protected function configure()
    {
        $this
            ->setName('cron:feed:charts:reset')
            ->setDescription("Reset Daily and 5min history datafeed");
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(DailyHistory::class);
        $this->truncateTable(TickerTaskDailyCharts::class);
        $this->truncateTable(TickerTaskMinuteCharts::class);
        $this->truncateTable(IntradayCounter::class);


        $mainTickers = $this->getRepository(MainTickers::class)->findAll();

        foreach ($mainTickers as $MainTicker) {

            /** @var MainTickers $MainTicker */
            DailyBarStorage::deleteBars($MainTicker->getId());
        }

        $this->addMessage(json_encode(['tickers for task'=> count($mainTickers)]));

    }




}
