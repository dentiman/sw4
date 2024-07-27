<?php

namespace Dentiman\ScheduleBundle\Command;

use Dentiman\ScheduleBundle\Entity\ScheduleLog;
use Dentiman\ScheduleBundle\Command\CronCommandInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request as TelegramRequest;
use Longman\TelegramBot\Telegram;

abstract class BasicScheduleCommand extends Command implements CronCommandInterface
{
    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em, ?string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
    }


    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param $className
     * @return ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    protected function getRepository($className)
    {
        return $this->getEntityManager()->getRepository($className);
    }

    protected function configure()
    {
        $this
            ->setName('schedule:basic')
            ->setDescription('Basic command to extend') ;
    }


    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scheduleLog = new ScheduleLog();
        $schedule = $this->getRepository("ScheduleBundle:Schedule")->findOneBy(["command" =>$this->getName()]);

        $scheduleLog->setSchedule($schedule);

        $scheduleLog->setStartedAt( new \DateTime('now'));

        try {
            $this->executeSchedule( $input, $output);
            $scheduleLog->setMessage(implode("; ",$this->messages));
            $scheduleLog->setIsSuccess(true);

        } catch (\Exception $exception) {

            $scheduleLog->setMessage(
                $exception->getMessage()." in ".$exception->getFile()." ".$exception->getLine()
            );
            $scheduleLog->setIsSuccess(false);

            $output->writeln("<error>".$exception->getMessage()."</error>");

            $this->sendSystemNotification(
                $this->getName().': '.substr($exception->getMessage(),0,200)
            );
        }

        $scheduleLog->setFinishedAt( new \DateTime('now'));

        $this->getEntityManager()->persist($scheduleLog);
        $this->getEntityManager()->flush();

        return 0;
    }

    protected function executeSchedule(InputInterface $input, OutputInterface $output)
    {
        throw new \LogicException('You must override the executeSchedule() method in the concrete command class.');
    }


    public function addMessage($message)
    {
        $this->messages[] = $message;
    }

    protected function sendSystemNotification(string $message)
    {
        try {
            $telegram = new Telegram("613211651:AAGZ-wT07vzJDANhbWHzASwTV9aMEs0ftk8", "dentiman_bot");

            $result = TelegramRequest::sendMessage([
                'chat_id' => '@dentiman_chat',
                'text' => $message
            ]);
        } catch (\Exception $exception) {

        }
    }

}
