<?php
namespace AppBundle\Form\PriceCalculator\Type;

use       Symfony\Component\Form\AbstractType;
use       Symfony\Component\Form\FormBuilderInterface;
use       Symfony\Component\OptionsResolver\OptionsResolver;
use       Symfony\Component\Form\FormEvent;
use       Symfony\Component\Form\FormEvents;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class Option extends AbstractType
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
            
            $option = $event->getData();
            $form   = $event->getForm();
            
            $form->add('amount', 'choice', [
                'choices' => range(0, $option->person)
            ]);
        });
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'part';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Form\PriceCalculator\Option',
        ]);
    }
}