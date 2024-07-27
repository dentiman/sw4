<?php

namespace App\Controller\Admin;

use Dentiman\PaymentBundle\Entity\GatewayConfig;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GatewayConfigCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GatewayConfig::class;
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
