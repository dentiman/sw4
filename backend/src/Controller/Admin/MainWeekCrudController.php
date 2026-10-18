<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainWeek;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MainWeekCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainWeek::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
