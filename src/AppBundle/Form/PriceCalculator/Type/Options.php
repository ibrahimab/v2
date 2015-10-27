<?php
namespace AppBundle\Form\PriceCalculator\Type;

use       Symfony\Component\Form\AbstractType;
use       Symfony\Component\Form\FormBuilderInterface;
use       Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class Options extends AbstractType
{
    /**
     * @param  FormBuilderInterface $builder
     * @param  array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('options', 'collection', [
            
            'type' => 'choice',
            'options' => [
                'choices' => [
                    'male', 'female',
                ],
            ],
        ]);
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'part';
    }
}