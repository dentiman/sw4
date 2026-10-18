<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Basic\FeedBasicFinviz;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FeedBasicFinvizCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeedBasicFinviz::class;
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
