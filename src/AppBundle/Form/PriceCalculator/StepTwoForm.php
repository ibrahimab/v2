<?php
namespace AppBundle\Form\PriceCalculator;

use       AppBundle\Form\PriceCalculator\Type\OptionGroup;
use       AppBundle\Form\PriceCalculator\Type\CancellationInsurance;
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

        $builder->add('person',  'hidden');
        $builder->add('weekend', 'hidden');
        $builder->add('booking', 'hidden');

        $builder->add('damage_insurance', 'choice', [
            'label'   => 'form.price_calculator.step_two.damage_insurance',
            'choices' => ['form.yes', 'form.no']
        ]);

        $builder->add('options', 'collection', [

            'label' => false,
            'type'  => new OptionGroup,
        ]);

        $builder->add('cancellation_insurances', 'collection', [

            'label' => false,
            'type'  => new CancellationInsurance,
        ]);

        $builder->add('save', 'submit', ['label' => 'form.price_calculator.step_two.submit']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'step_two';
    }
}