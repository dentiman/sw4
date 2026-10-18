<?php

namespace Dentiman\PaymentBundle\Form\Type;

use Payum\Core\Model\GatewayConfigInterface;
use Dentiman\PaymentBundle\Entity\GatewayConfig;
use Dentiman\PaymentBundle\Form\Registry\FormTypeRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class GatewayConfigType extends AbstractType
{
    /** @var FormTypeRegistryInterface */
    private $gatewayConfigurationTypeRegistry;

    /**
     * {@inheritdoc}
     */
    public function __construct(

        FormTypeRegistryInterface $gatewayConfigurationTypeRegistry
    ) {
        $this->gatewayConfigurationTypeRegistry = $gatewayConfigurationTypeRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('factoryName', null, array('required' => false, 'label' => 'dentiman.paymentbundle.gatewayconfig.factoryname.label'))
                ->add('gatewayName', null, array('required' => false, 'label' => 'dentiman.paymentbundle.gatewayconfig.gatewayname.label'))
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $gatewayConfig = $event->getData();

                if (!$gatewayConfig instanceof GatewayConfig ) {
                    return;
                }

                if(!$gatewayConfig->getFactoryName()) {
                    return;
                }

                if (!$this->gatewayConfigurationTypeRegistry->has('gateway_config', $gatewayConfig->getFactoryName())) {
                    return;
                }

                $configType = $this->gatewayConfigurationTypeRegistry->get('gateway_config', $gatewayConfig->getFactoryName());
                $event->getForm()->add('config', $configType, [
                    'label' => false,
                    'auto_initialize' => false,
                ]);
            })
        ;
        $builder->add('save', SubmitType::class, [
            'attr' => ['class' => 'save'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dentiman\PaymentBundle\Entity\GatewayConfig'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dentiman_paymentbundle_gatewayconfig';
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}
