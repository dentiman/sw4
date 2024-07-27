<?php

namespace App\Command;

use App\Entity\User;
use Dentiman\PaymentBundle\Entity\GatewayConfig;
use Dentiman\PaymentBundle\Entity\Service;
use Dentiman\PaymentBundle\Entity\ServiceVariant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ImportInitLiqpayConfigCommand extends Command
{
    protected static $defaultName = 'import:liqpay';

    private $entityManager;

    private $passwordHasher;
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $gatewayConfig = new GatewayConfig();
        $gatewayConfig->setConfig(['public_key' => 'i35363244346', 'private_key' => 'PqvjNcCvmKf6StJVBukLYtSRMyovsOqR6MJN3wKt']);
        $gatewayConfig->setFactoryName('liqpay');
        $gatewayConfig->setGatewayName('liqpay');
        $this->entityManager->persist($gatewayConfig);
        $this->entityManager->flush();
        return Command::SUCCESS;
    }
}
