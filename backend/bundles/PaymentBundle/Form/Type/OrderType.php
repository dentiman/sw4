<?php

namespace Dentiman\PaymentBundle\Form\Type;

use Dentiman\PaymentBundle\Entity\Order;
use Dentiman\PaymentBundle\Entity\ServiceVariant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orderItem',EntityType::class,
                [
                    'required' => true,
                    'class' => ServiceVariant::class,
                    'choice_label' => 'name',
                    'label' => 'app.entity.order_item.singular'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
        return 'dentiman_paymentbundle_order';
    }
}
