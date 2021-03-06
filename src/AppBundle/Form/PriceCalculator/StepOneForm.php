<?php
namespace AppBundle\Form\PriceCalculator;
use       Symfony\Component\Form\AbstractType;
use       Symfony\Component\Form\FormBuilderInterface;

/**
 * @author  Ibrahim Abdullah
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class StepOneForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();

        $builder->add('person', 'choice', [

            'choices'     => $entity->persons,
            'placeholder' => '',
            'label'       => 'form.price_calculator.step_one.person',
        ]);

        $builder->add('weekend', 'choice', [

            'choices'     => $entity->weekends,
            'placeholder' => '',
            'label'       => 'form.price_calculator.step_one.weekend',
        ]);

        $builder->add('save', 'submit', [
            'label' => 'form.price_calculator.step_one.submit',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'step_one';
    }
}