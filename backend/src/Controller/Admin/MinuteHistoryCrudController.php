<?php

namespace App\Controller\Admin;

use App\Entity\FeedImport\Charts\MinuteHistory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MinuteHistoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MinuteHistory::class;
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
