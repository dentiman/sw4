<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Charts\IntradayCounter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IntradayCounterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return IntradayCounter::class;
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
