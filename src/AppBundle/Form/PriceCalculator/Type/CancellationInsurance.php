<?php
namespace AppBundle\Form\PriceCalculator\Type;

use       Symfony\Component\Form\AbstractType;
use       Symfony\Component\Form\FormEvent;
use       Symfony\Component\Form\FormEvents;
use       Symfony\Component\Form\FormBuilderInterface;
use       Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class CancellationInsurance extends AbstractType
{
    /**
     * @param  FormBuilderInterface $builder
     * @param  array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            
            $entity = $event->getData();
            $form   = $event->getForm();
            
            $form->add('amount', 'choice', [
                'label' => false,
                'choices' => range(0, $entity->person),
                'choice_label' => null,
            ]);
        });
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'cancellation_insurances';
    }
    
    /**
     * @param  OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Form\PriceCalculator\CancellationInsurance',
        ]);
    }
}