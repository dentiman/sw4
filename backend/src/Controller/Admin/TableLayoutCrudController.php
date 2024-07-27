<?php

namespace App\Controller\Admin;

use App\Entity\Presets\TableLayout;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TableLayoutCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TableLayout::class;
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
