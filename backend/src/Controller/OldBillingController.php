<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Dentiman\PaymentBundle\Entity\GatewayConfig;
use Dentiman\PaymentBundle\Entity\Order;
use Dentiman\PaymentBundle\Entity\ServiceVariant;
use Dentiman\PaymentBundle\Entity\Payment;
use Dentiman\PaymentBundle\ServiceProcessor\OrderPaymentProcessor;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class OldBillingController extends AbstractController
{

    /**
     * @Route("/", name="index_app")
     */
    public function tmpIndexPage()
    {
        return $this->redirect('https://www.stock-watcher.com');
    }

    /**
     * @Route("/api/co/{gatewayConfig}/email/{email}", name="old_billing")
     */
    public function createOrderWithCaptureUrl(
        GatewayConfig $gatewayConfig,
        $email,
        OrderPaymentProcessor $orderPaymentProcessor,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['email'=> $email]);

        if(!$user instanceof User) {
            $user = new User();
            $user->setUsername($email);
            $user->setEmail($email);
            $user->setPassword($passwordHasher->hashPassword($user,'K12345-**)'));
            $this->getDoctrine()->getManager()->persist($user);
        }

        $serviceVariant = $this->getDoctrine()->getRepository("PaymentBundle:ServiceVariant")->find(1);

        if(!$serviceVariant instanceof  ServiceVariant) {
            return new JsonResponse(['error'=> 'no service variant']);
        }

        $order = new Order();
        $order->setServiceVariant($serviceVariant);
        $order->setTotal($serviceVariant->getPrice());
        $order->setOwner($user);

        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();

        $captureToken = $orderPaymentProcessor->createPaymentCaptureToken( $order,  $gatewayConfig);
        return $this->redirect($captureToken->getTargetUrl());
    }
}
