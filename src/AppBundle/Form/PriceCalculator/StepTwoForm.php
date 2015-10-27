<?php
namespace AppBundle\Form\PriceCalculator;

use       AppBundle\Form\PriceCalculator\Type\Options;
use       Symfony\Component\Form\AbstractType;
use       Symfony\Component\Form\FormBuilderInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class StepTwoForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        
        $builder->add('options', new Options)
                ->add('save', 'submit', ['label' => 'Totaalbedrag berekenen']);
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'step_two';
    }
}