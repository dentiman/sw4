<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Tickers\OtherListed;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OtherListedCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OtherListed::class;
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
