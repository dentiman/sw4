<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainGridDelay;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MainGridDelayCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainGridDelay::class;
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
