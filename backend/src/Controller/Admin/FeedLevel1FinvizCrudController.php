<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Level1\FeedLevel1Finviz;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FeedLevel1FinvizCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeedLevel1Finviz::class;
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
