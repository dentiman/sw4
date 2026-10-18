<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Basic\FeedBasicTickers;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FeedBasicTickersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeedBasicTickers::class;
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
