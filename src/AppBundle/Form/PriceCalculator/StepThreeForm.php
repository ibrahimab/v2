<?php
namespace AppBundle\Form\PriceCalculator;

use       AppBundle\Form\PriceCalculator\Type\HiddenOptionGroup;
use       AppBundle\Form\PriceCalculator\Type\HiddenCancellationInsurance;
use       Symfony\Component\Form\AbstractType;
use       Symfony\Component\Form\FormBuilderInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class StepThreeForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();

        $builder->add('person',  'hidden');
        $builder->add('weekend', 'hidden');
        $builder->add('booking', 'hidden');
        $builder->add('damage_insurance', 'hidden');

        $builder->add('options', 'collection', [

            'label' => false,
            'type'  => new HiddenOptionGroup,
        ]);

        $builder->add('cancellation_insurances', 'collection', [

            'label' => false,
            'type'  => new HiddenCancellationInsurance,
        ]);

        $builder->add('email', 'email', ['label' => 'form.price_calculator.step_three.email']);
        $builder->add('save', 'submit', ['label' => 'form.price_calculator.step_three.submit', 'attr' => ['data-action' => 'price-calculator-send-mail']]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'step_three';
    }
}