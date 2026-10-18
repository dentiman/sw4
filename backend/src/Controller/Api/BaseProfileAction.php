<?php


namespace App\Controller\Api;


use App\Entity\Feed\MainTickers;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class BaseProfileAction
{
    protected $security;

    protected $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    protected function loadHistoryQuotes(User $data)
    {
        /** @var ServiceEntityRepository $rep */
        $rep = $this->entityManager->getRepository(MainTickers::class);
        $mainTickers = $rep->createQueryBuilder('m')
            ->where('m.id IN (:tickers)')
            ->setParameter('tickers', $data->getHistoryTickers())
            ->getQuery()
            ->getResult();
        $historyQuotes = [];

        foreach ($data->getHistoryTickers() as $historyTicker) {
            foreach ($mainTickers as $mainTicker) {
                if($historyTicker === $mainTicker->getId())
                    $historyQuotes[] = $mainTicker;
            }
        }
        $data->setHistoryQuotes($historyQuotes);
    }

}