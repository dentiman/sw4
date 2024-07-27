<?php
namespace App\Command\FeedCalculation;

use App\Command\FeedImport\BaseFeedImportCommand;
use App\DataFeedApp\Tools\FeedCalculator;
use App\Entity\Feed\MainTmp;
use App\Entity\FeedImport\Charts\DailyHistory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AtrCommand extends BaseFeedImportCommand
{
    public function getDefaultDefinition()
    {
        return '*/1 * * * *';
    }

    protected function configure()
    {
        $this
            ->setName('cron:feed:calculate:atr')
            ->setDescription("ATR Calculate");
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $this->getBaseTickers();
        foreach ($this->getBaseTickers() as $ticker) {

            $DailyHistoriesForAtr = $this->getEntityManager()
                ->getRepository(DailyHistory::class)
                ->findBy(['ticker'=>$ticker]);

            if(count($DailyHistoriesForAtr) == 0 ) continue;

            $atrValue = FeedCalculator::ATR(...$DailyHistoriesForAtr);

            $MainTmp =  $this->getEntityManager()->getRepository(MainTmp::class)->find($ticker);
            if($MainTmp instanceof MainTmp) {
                $MainTmp->setAtr($atrValue);
            }
            $output->writeln( $ticker.': '. $atrValue);

        }

        $this->addMessage(json_encode(['count'=> count($DailyHistoriesForAtr)]));

    }

}
