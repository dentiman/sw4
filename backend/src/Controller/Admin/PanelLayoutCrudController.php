<?php

namespace App\Controller\Admin;

use App\Entity\Presets\PanelLayout;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PanelLayoutCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PanelLayout::class;
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
