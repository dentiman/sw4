<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Basic\FeedBasicCalculatedGrid;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FeedBasicCalculatedGridCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeedBasicCalculatedGrid::class;
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
