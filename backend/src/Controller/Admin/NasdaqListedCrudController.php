<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Tickers\NasdaqListed;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NasdaqListedCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NasdaqListed::class;
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
