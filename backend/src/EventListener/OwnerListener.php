<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2020-01-05
 * Time: 18:58
 */

namespace App\EventListener;

use App\Entity\OwnerInterface;
use App\Entity\Presets\ScreenerFilter;
use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class OwnerListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist( LifecycleEventArgs $event)
    {
        $entity = $event->getObject();

        if (!$entity instanceof OwnerInterface || $entity->getOwner() instanceof User || null === $user = $this->security->getUser()) {
            return;
        }

        $entity->setOwner($user);

    }
}
