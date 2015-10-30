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

        $builder->add('person', 'choice', ['choices' => $entity->persons, 'placeholder' => ''])
                ->add('weekend', 'choice', ['choices' => $entity->weekends, 'placeholder' => ''])
                ->add('save', 'submit', ['label' => 'Volgende']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'step_one';
    }
}