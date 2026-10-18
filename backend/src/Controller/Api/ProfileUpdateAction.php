<?php

namespace App\Controller\Api;

use App\Entity\Feed\MainTickers;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ClientGetItemAction
 * @package App\Controller\User
 */
class ProfileUpdateAction extends BaseProfileAction
{

    public function __invoke(User $data): UserInterface
    {
        if ($data->getNewTicker()) {
            $data->addHistoryTicker($data->getNewTicker());

        }
        $data->setLastLogin(new \DateTime());
        $this->loadHistoryQuotes($data);
        return $data;
    }
}
