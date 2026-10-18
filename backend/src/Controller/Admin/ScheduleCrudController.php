<?php

namespace App\Controller\Admin;

use Dentiman\ScheduleBundle\Entity\Schedule;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
class ScheduleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Schedule::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('command'),
            TextField::new('definition'),
            'isEnabled'
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $runCommand = Action::new('runCommand', 'run', 'fa fa-envelope')
            // if the route needs parameters, you can define them:
            // 1) using an array
            ->linkToRoute('run_command', function (Schedule $schedule): array {
                return [
                    'command' =>$schedule->getCommand(),
                ];
            });
        return $actions
            // ...
            ->add(Crud::PAGE_EDIT,  $runCommand );
    }

}
