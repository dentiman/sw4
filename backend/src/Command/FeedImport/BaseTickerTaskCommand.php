<?php
namespace App\Command\FeedImport;

use App\Entity\FeedImport\Basic\FeedBasicTickers;
use App\Model\Feed\TickerTaskInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseTickerTaskCommand extends BaseFeedImportCommand
{
    protected $ticketTaskClass;

    /**
     * @var int $limit
     */
    protected $limit = 5;

    public function getDefaultDefinition()
    {
        return '*/1 * * * *';
    }


    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();

        $messageData = ['success'=>0, 'failed'=>0,'failed_tickers'=>[]];

        /** @var FeedBasicTickers[] $FeedBasicTickers */
        $FeedBasicTickers = $qb
            ->select('baseTicker')
            ->from(FeedBasicTickers::class,'baseTicker')
            ->leftJoin($this->ticketTaskClass,'ticketTask', 'WITH', 'baseTicker.id = ticketTask.id')
            ->where($qb->expr()->orX(
                $qb->expr()->isNull('ticketTask.id'),
                $qb->expr()->andX(
                    $qb->expr()->lte('ticketTask.attempt', 5),
                    $qb->expr()->neq('ticketTask.done', true)
                )

            ))
            ->orderBy('ticketTask.updatedAt', 'ASC')
            ->addOrderBy('baseTicker.id', 'ASC')
            ->setMaxResults($this->limit)
            ->getQuery()
            ->getResult()
            ;

        foreach ($FeedBasicTickers as $FeedBasicTicker) {

            $output->writeln($FeedBasicTicker->getId());

            /** @var TickerTaskInterface $tickerTaskEntity */
           $tickerTaskEntity = $this->getRepository($this->ticketTaskClass)
                ->find($FeedBasicTicker->getId());

           if(!$tickerTaskEntity) {
                $tickerTaskEntity = new $this->ticketTaskClass();
                $tickerTaskEntity->setId($FeedBasicTicker->getId());
           }
            $tickerTaskEntity->addAttempt();

            try {
                $this->executeTickerTask($tickerTaskEntity);
                if($tickerTaskEntity->isSuccess()) $messageData['success'] ++;
            } catch (\Exception $exception) {
                $messageData['failed'] ++;
                $messageData['failed_tickers'][] =$tickerTaskEntity->getId();
                $messageData['last_exception'] = $exception->getMessage();
                $tickerTaskEntity->setMessage($exception->getMessage());
                if($tickerTaskEntity->getAttempt() >= 5) {
                    $tickerTaskEntity->markDone();
                }
            }

           $this->getEntityManager()->persist($tickerTaskEntity);
        }

        $this->addMessage(json_encode($messageData));
    }


    protected function executeTickerTask(TickerTaskInterface $tickerTask)
    {
        throw new \LogicException('You must overwrite this action');
    }

}
