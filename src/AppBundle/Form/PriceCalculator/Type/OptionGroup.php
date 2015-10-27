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
class OptionGroup extends AbstractType
{
    /**
     * @param  FormBuilderInterface $builder
     * @param  array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parts', 'collection', [
            'type' => new Option(),
        ]);
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
            'data_class' => 'AppBundle\Entity\Form\PriceCalculator\OptionGroup',
        ]);
    }
}