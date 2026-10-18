<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainPremarket;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class MainPremarketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainPremarket::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
             IdField::new('id'),
            'pvol',
            'pchp',
            'ptcount',
            DateTimeField::new('pttime'),
        ];
    }
}
