<?php

namespace Dentiman\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Payum\Core\Model\Identity;
use Payum\Core\Security\TokenInterface;
use Payum\Core\Storage\IdentityInterface;
use Payum\Core\Security\Util\Random;
use Payum\Core\Model\Token;

/**
 * PaymentTokens
 *
 * @ORM\Table(name="payment_token")
 * @ORM\Entity
 */
class PaymentToken extends Token
{
}
