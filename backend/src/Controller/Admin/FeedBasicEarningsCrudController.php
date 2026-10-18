<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Basic\FeedBasicEarnings;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class FeedBasicEarningsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeedBasicEarnings::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('earn'),
        ];
    }
}
