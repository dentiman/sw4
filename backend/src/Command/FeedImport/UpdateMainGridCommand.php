<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 11.12.16
 * Time: 15:37
 */

namespace App\Command\FeedImport;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class UpdateMainGridCommand extends BaseFeedImportCommand
{

    public function getDefaultDefinition()
    {
        return '50 1 * * 1-5';
    }


    protected function configure()
    {
        $this
            ->setName('cron:feed:grid:update')
            ->setDescription('Update basic data') ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $this->updateBaseGrid();
    }

}
