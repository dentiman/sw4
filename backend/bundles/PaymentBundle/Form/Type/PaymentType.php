<?php

namespace Dentiman\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Oro\Bundle\FormBundle\Form\Type\OroDateTimeType;
use Oro\Bundle\FormBundle\Form\Type\OroDateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Oro\Bundle\FormBundle\Form\Type\OroChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Oro\Bundle\TranslationBundle\Form\Type\TranslatableEntityType;
use Oro\Bundle\FormBundle\Form\Type\EntityIdentifierType;

class PaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('number', null, array('required' => false, 'label' => 'dentiman.paymentbundle.payment.number.label'))
                ->add('description', null, array('required' => false, 'label' => 'dentiman.paymentbundle.payment.description.label'))
                ->add('clientEmail', null, array('required' => false, 'label' => 'dentiman.paymentbundle.payment.clientemail.label'))
                ->add('clientId', null, array('required' => false, 'label' => 'dentiman.paymentbundle.payment.clientid.label'))
                ->add('totalAmount', null, array('required' => false, 'label' => 'dentiman.paymentbundle.payment.totalamount.label'))
                ->add('currencyCode', null, array('required' => false, 'label' => 'dentiman.paymentbundle.payment.currencycode.label'))


        ;

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dentiman\PaymentBundle\Entity\Payment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dentiman_paymentbundle_payment';
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}
