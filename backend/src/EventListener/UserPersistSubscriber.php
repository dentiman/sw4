<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Longman\TelegramBot\Request as TelegramRequest;
use Longman\TelegramBot\Telegram;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPersistSubscriber
{
    private $passwordHasher;

    public function __construct( UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {

        $data = $args->getObject();
        if(!$data instanceof User) return;
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->passwordHasher->hashPassword($data,$data->getPlainPassword())
            );
            $data->eraseCredentials();
        }
        try {

            $telegram = new Telegram("613211651:AAGZ-wT07vzJDANhbWHzASwTV9aMEs0ftk8", "dentiman_bot");
            $result = TelegramRequest::sendMessage([
                'chat_id' => '-1001370496379',
                'text' => 'registered:'.$data->getEmail()
            ]);
        } catch (\Exception $exception) {

        }

    }


}
