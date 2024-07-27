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

class ImportInitDataCommand extends Command
{
    protected static $defaultName = 'import:init-data';

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

        $user = new User();

        $user->setUsername('dentiman');
        $user->setEmail('den.timanovskiy@gmail.com');
        $user->setPassword( $this->passwordHasher->hashPassword(  $user,'www'));
        $user->setRoles(['ROLE_ADMIN']);
        $this->entityManager->persist($user);

        $user2 = new User();
        $user2->setUsername('dentiman2');
        $user2->setEmail('support@stock-watcher.com');
        $user2->setPassword( $this->passwordHasher->hashPassword(  $user2,'www'));
        $user2->setRoles(['ROLE_ADMIN']);
        $this->entityManager->persist($user2);

        $service = new Service();
        $service->setCode('premium');
        $service->setName('Premium');
        $service->setIsEnabled(true);
        $this->entityManager->persist($service);

        $serviceVariant = new ServiceVariant();
        $serviceVariant->setIsEnabled(true);
        $serviceVariant->setName('1 month');
        $serviceVariant->setPrice(15);
        $serviceVariant->setExpiration(30);
        $serviceVariant->setService($service);
        $this->entityManager->persist($serviceVariant);

        $serviceVariant = new ServiceVariant();
        $serviceVariant->setIsEnabled(true);
        $serviceVariant->setName('6 month');
        $serviceVariant->setPrice(80);
        $serviceVariant->setExpiration(180);
        $serviceVariant->setService($service);
        $this->entityManager->persist($serviceVariant);

        $serviceVariant = new ServiceVariant();
        $serviceVariant->setIsEnabled(true);
        $serviceVariant->setName('1 year');
        $serviceVariant->setPrice(150);
        $serviceVariant->setExpiration(365);
        $serviceVariant->setService($service);
        $this->entityManager->persist($serviceVariant);



        $gatewayConfig = new GatewayConfig();
        $usd = [
            'LMI_PAYEE_PURSE' => 'Z296056246139',
            'label' => 'WebMoney WMZ'
        ];
        $gatewayConfig->setConfig($usd);
        $gatewayConfig->setFactoryName('webmoney');
        $gatewayConfig->setGatewayName('webmoney');
        $this->entityManager->persist($gatewayConfig);

        $gatewayConfig = new GatewayConfig();
        $rub = [
            'LMI_PAYEE_PURSE' => 'R794513959542',
            'label' => 'WebMoney WMR'
        ];
        $gatewayConfig->setConfig($rub);
        $gatewayConfig->setFactoryName('webmoney');
        $gatewayConfig->setGatewayName('webmoney');
        $this->entityManager->persist($gatewayConfig);

        $this->entityManager->flush();
        return Command::SUCCESS;
    }
}
