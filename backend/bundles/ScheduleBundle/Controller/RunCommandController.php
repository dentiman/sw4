<?php

namespace Dentiman\ScheduleBundle\Controller;

use Dentiman\ScheduleBundle\Entity\Schedule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;


class RunCommandController extends AbstractController
{
    protected  $em;
    protected  $kernel;

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel)
    {
        $this->em = $entityManager;
        $this->kernel = $kernel;
    }

    /**
     * @Route("/run", name="schedule_run_command")
     */
    public function indexAction(Request $request)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);


        $id = $request->query->get('id');
        $schedule =  $this->em->getRepository('ScheduleBundle:Schedule')->find($id);

        if($schedule instanceof Schedule) {
            $input = new ArrayInput([
                'command' => $schedule->getCommand(),
            ]);

            // You can use NullOutput() if you don't need the output
            $output = new BufferedOutput();
            $application->run($input, $output);
        }

        return $this->redirectToRoute('easyadmin');
    }
}
