<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainTmp;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class MainTmpCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainTmp::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('ipo'),
            'sector',
            'atr'
        ];
    }
}
