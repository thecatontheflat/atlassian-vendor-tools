<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')

            ->add('addonKey', null, ['required' => false])

            ->add('template')

            ->add('licenseType', 'choice', [
                'choices' => [
                    'COMMERCIAL',
                    'EVALUATION'
                ]
            ])

            ->add('licenseField', 'choice', [
                'choices' => [
                    'startDate',
                    'endDate'
                ]
            ])

            ->add('shiftDays');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_event';
    }
}
