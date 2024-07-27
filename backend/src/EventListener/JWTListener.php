<?php


namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Longman\TelegramBot\Request as TelegramRequest;
use Longman\TelegramBot\Telegram;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;


class JWTListener
{
    protected $requestStack;
    protected $security;
    protected  $entityManager;

    public function __construct(RequestStack $requestStack, Security $security, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->security = $security;
        $this->entityManager =  $entityManager;
    }

    public function onJWTDecoded(JWTDecodedEvent $event)
    {
        $payload = $event->getPayload();
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail($payload['email']);
        if(!$user) $event->markAsInvalid();
        if (!isset($payload['vid']) || $payload['vid'] !== $user->getValidateId()) {
            $clientIp = $this->requestStack->getCurrentRequest()->getClientIp();
            if(!$clientIp || $clientIp !== $user->getClientIp())
            $event->markAsInvalid();
        }
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload       = $event->getData();
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail($payload['email']);
        if(!$user) throw new \Exception('User not found from payload');
        $payload['vid'] = random_int(0,10000000);
        $user->setClientIp($this->requestStack->getCurrentRequest()->getClientIp());
        $user->setValidateId( $payload['vid']);
        $user->setLastLogin(new \DateTime());
        $this->entityManager->flush();
        $event->setData($payload);

        $header = $event->getHeader();
        $event->setHeader($header);

        $telegram = new Telegram("613211651:AAGZ-wT07vzJDANhbWHzASwTV9aMEs0ftk8", "dentiman_bot");
        $result = TelegramRequest::sendMessage([
            'chat_id' => '-1001370496379',
            'text' => 'login:'. $user->getEmail()
        ]);
    }
}
