<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Basic\FeedBasicCalculatedData;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FeedBasicCalculatedDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeedBasicCalculatedData::class;
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
