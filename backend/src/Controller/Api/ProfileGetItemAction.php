<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 * Class ClientGetItemAction
 * @package App\Controller\User
 */
class ProfileGetItemAction extends BaseProfileAction
{

    /**
     * @return UserInterface
     */
    public function __invoke(): UserInterface
    {

       $user = $this->security->getUser();

        if (!$user) {
            throw new NotFoundHttpException();
        }
        $this->loadHistoryQuotes($user);

        return $user;
    }
}
