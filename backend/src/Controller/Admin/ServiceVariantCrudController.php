<?php

namespace App\Controller\Admin;

use Dentiman\PaymentBundle\Entity\ServiceVariant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ServiceVariantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ServiceVariant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'name',
            'expiration',
            'price',
            AssociationField::new('service'),
        ];
    }
}
