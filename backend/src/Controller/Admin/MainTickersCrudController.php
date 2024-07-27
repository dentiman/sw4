<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainTickers;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MainTickersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainTickers::class;
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
