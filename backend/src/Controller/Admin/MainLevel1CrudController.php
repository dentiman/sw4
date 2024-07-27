<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainLevel1;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class MainLevel1CrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainLevel1::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            'price',
            'chp',
            'ch',
            'vol',
            DateTimeField::new('ttime'),
        ];
    }
}
