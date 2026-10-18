<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainMinutePrev;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MainMinutePrevCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainMinutePrev::class;
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
