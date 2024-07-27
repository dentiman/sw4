<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainTech;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class MainTechCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainTech::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            'averageDailyVolume3Month',
            'marketCap',
        ];
    }
}
