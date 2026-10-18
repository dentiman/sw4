<?php

namespace Dentiman\ScheduleBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Dentiman\ScheduleBundle\Engine\CommandRunnerInterface;
use Dentiman\ScheduleBundle\Entity\Schedule;
use Dentiman\ScheduleBundle\Helper\CronHelper;
use Dentiman\ScheduleBundle\Tools\CommandRunner;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Cron commands launcher.
 */
class CronCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'app:cron';

    /** @var ManagerRegistry */
    private $registry;


    /** @var CronHelper */
    private $cronHelper;


    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $environment;

    /**
     * @param ManagerRegistry $registry
     * @param CronHelper $cronHelper
     * @param LoggerInterface $logger
     * @param string $environment
     */
    public function __construct(
        ManagerRegistry $registry,
        CronHelper $cronHelper,
        LoggerInterface $logger,
        string $environment
    ) {
        parent::__construct();

        $this->registry = $registry;
        $this->cronHelper = $cronHelper;
        $this->logger = $logger;
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Cron commands launcher');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schedules = $this->getAllSchedules();

        /** @var Schedule $schedule */
        foreach ($schedules as $schedule) {
            $cronExpression = $this->cronHelper->createCron($schedule->getDefinition());
            if ($cronExpression->isDue()) {

                /** @var CronCommandInterface $command */
                $command = $this->getApplication()->get($schedule->getCommand());

                if ($command instanceof CronCommandInterface && !$schedule->isEnabled()) {
                    $output->writeln(
                        'Skipping not enabled command ' . $schedule->getCommand(),
                        OutputInterface::VERBOSITY_DEBUG
                    );
                    continue;
                }
                $output->writeln(
                    'Running synchronous command ' . $schedule->getCommand(),
                    OutputInterface::VERBOSITY_DEBUG
                );
                CommandRunner::runCommand(
                    $schedule->getCommand(),
                    array_merge(
                        $schedule->getArguments(),
                        ['--env' => $this->environment]
                    )
                );

            } else {
                $output->writeln('Skipping not due command '.$schedule->getCommand(), OutputInterface::VERBOSITY_DEBUG);
            }
        }

        $output->writeln('All commands scheduled', OutputInterface::VERBOSITY_DEBUG);

        return 0;
    }

    /**
     * Convert command arguments to options. It needed for correctly pass this arguments into ArrayInput:
     * new ArrayInput(['name' => 'foo', '--bar' => 'foobar']);
     *
     * @param array $commandOptions
     * @return array
     */
    protected function resolveOptions(array $commandOptions)
    {
        $options = [];
        foreach ($commandOptions as $key => $option) {
            $params = explode('=', $option, 2);
            if (is_array($params) && count($params) === 2) {
                $options[$params[0]] = $params[1];
            } else {
                $options[$key] = $option;
            }
        }
        return $options;
    }

    /**
     * @param string $className
     * @return ObjectManager
     */
    protected function getEntityManager($className)
    {
        return $this->registry->getManagerForClass($className);
    }

    /**
     * @param string $className
     * @return ObjectRepository
     */
    private function getRepository($className)
    {
        return $this->getEntityManager($className)->getRepository($className);
    }

    /**
     * @return ArrayCollection|Schedule[]
     */
    private function getAllSchedules()
    {
        return new ArrayCollection($this->getRepository('ScheduleBundle:Schedule')->findAll());
    }
}
