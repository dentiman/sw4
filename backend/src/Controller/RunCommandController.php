<?php

namespace App\Controller;

use App\Controller\Admin\ScheduleCrudController;
use App\Message\RunCommandMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
class RunCommandController extends AbstractController
{

    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * @Route("api/admin/run-command", name="run_command")
     */
    public function index(Request $request): Response
    {
       $command =  $request->get('command');
        // or use the shortcut
        $this->dispatchMessage(new RunCommandMessage($command));
        $url =  $this->adminUrlGenerator
            ->setController(ScheduleCrudController::class)
            ->setAction('index')
            ->generateUrl();
       return  $this->redirect($url );

    }
}
