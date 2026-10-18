<?php

namespace Dentiman\PaymentBundle\Form\Type;


use Dentiman\PaymentBundle\Entity\ServiceVariant;
use Dentiman\PaymentBundle\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class,['required' => false,'label' =>'app.entity.order_item.name'])
        ->add('price',null,[ 'required' => false, 'label' => 'app.entity.order_item.price'])
        ->add('expiration',null,[ 'required' => false, 'label' => 'app.entity.order_item.expiration'])
        ->add('isEnabled',null,[ 'required' => false, 'label' => 'app.entity.order_item.isEnabled'])
        ->add('service',EntityType::class,
            [
                'required' => true,
                'class' => Service::class,
                'choice_label' => 'name',
                'label' => 'app.entity.service.singular'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ServiceVariant::class,
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
        return 'dentiman_paymentbundle_orderitem';
    }
}
