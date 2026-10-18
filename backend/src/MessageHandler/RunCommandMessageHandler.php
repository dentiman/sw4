<?php

namespace App\MessageHandler;

use App\Message\RunCommandMessage;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
final class RunCommandMessageHandler implements MessageHandlerInterface
{
    protected $kernel;

    public function __construct( KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function __invoke(RunCommandMessage $message)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => $message->getCommandName(),
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new  NullOutput();
        $application->run($input, $output);
    }
}
