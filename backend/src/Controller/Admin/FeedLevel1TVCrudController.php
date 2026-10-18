<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Level1\FeedLevel1TV;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FeedLevel1TVCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeedLevel1TV::class;
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
