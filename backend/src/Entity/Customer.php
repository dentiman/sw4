<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-11-17
 * Time: 13:36
 */

namespace App\Entity;


use Dentiman\PaymentBundle\Entity\CustomerInterface;
use Payum\Core\Model\CreditCardInterface;

class Customer implements CustomerInterface
{
    private $email;


    public function getEmail()
    {
         return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getFullName()
    {
        // TODO: Implement getFullName() method.
    }

    public function setFullName(string $fullName)
    {
        // TODO: Implement setFullName() method.
    }

    public function getCreditCard(): CreditCardInterface
    {
        // TODO: Implement getCreditCard() method.
    }

    public function setCreditCard(CreditCardInterface $creditCard)
    {
        // TODO: Implement setCreditCard() method.
    }

}
