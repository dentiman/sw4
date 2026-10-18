<?php

namespace Dentiman\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\CreditCardInterface;

interface  CustomerInterface
{
    public function getEmail();
    public function setEmail(string $email);
    public function getFullName();
    public function setFullName(string $fullName);

    public function getCreditCard(): CreditCardInterface ;
    public function setCreditCard(CreditCardInterface $creditCard);
}
