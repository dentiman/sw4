<?php
namespace Dentiman\ScheduleBundle\Command;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Dentiman\ScheduleBundle\Command\CronCommandInterface;
use Dentiman\ScheduleBundle\Entity\Schedule;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Loads cron commands definitions from application to database
 */
class CronDefinitionsLoadCommand extends Command
{

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
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cron:definitions:load')
            ->setDescription('Loads cron commands definitions from application to database.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Removing all previously loaded commands...</info>');
        $this->em->getRepository('ScheduleBundle:Schedule')->createQueryBuilder('d')->delete()->getQuery()->execute();

        $applicationCommands = $this->getApplication()->all('cron');


        foreach ($applicationCommands as $name => $command) {
            $output->write(sprintf('Processing command "<info>%s</info>": ', $name));
            if ($this->checkCommand($output, $command)) {
                $schedule = $this->createSchedule($output, $command, $name);
                $this->em->persist($schedule);
            }
        }

        $this->em->flush();

        return 0;
    }

    /**
     * @param OutputInterface $output
     * @param CronCommandInterface $command
     * @param string $name
     * @param array $arguments
     *
     * @return Schedule
     */
    private function createSchedule(
        OutputInterface $output,
        CronCommandInterface $command,
        $name,
        array $arguments = []
    ) {
        $output->writeln('<comment>setting up schedule..</comment>');

        $schedule = new Schedule();
        $schedule
            ->setCommand($name)
            ->setDefinition($command->getDefaultDefinition())
            ->setArguments($arguments);

        return $schedule;
    }

    /**
     * @param OutputInterface $output
     * @param Command $command
     *
     * @return bool
     */
    private function checkCommand(OutputInterface $output, Command $command)
    {
        if (!$command instanceof CronCommandInterface) {
            $output->writeln(
                '<info>Skipping, the command does not implement CronCommandInterface</info>'
            );

            return false;
        }

        if (!$command->getDefaultDefinition()) {
            $output->writeln('<error>no cron definition found, check command</error>');

            return false;
        }

        return true;
    }


}
