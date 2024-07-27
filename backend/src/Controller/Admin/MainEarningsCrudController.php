<?php

namespace App\Controller\Admin;

use App\Entity\Feed\MainEarnings;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class MainEarningsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainEarnings::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('earn'),
        ];
    }
}
