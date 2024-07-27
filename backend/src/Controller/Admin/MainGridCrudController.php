<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainGrid;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MainGridCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainGrid::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            'exchange',
            'price',
            'chp',
            'ch',
            'vol',
            'level',
            'newHigh',
            'newLow',
            DateTimeField::new('ttime'),
        ];
    }

}
