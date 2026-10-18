<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 11.12.16
 * Time: 15:37
 */

namespace App\Command\FeedImport;

use Symfony\Component\Console\Input\InputArgument;


class UpdateCalendarCommand extends UpdateEarningsCommand
{

    protected $days = 8;

    protected function configure()
    {
        $this
            ->setName('cron:feed:earnings:calendar')
            ->setDescription('Update next week earnings for tickers or current earnings')
            ->addArgument('days', InputArgument::OPTIONAL, 'days 5');
    }

}
