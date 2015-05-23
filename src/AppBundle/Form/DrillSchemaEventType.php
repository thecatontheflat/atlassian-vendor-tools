<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DrillSchemaEventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('dateShift')
            ->add('emailTemplate')
            ->add('emailSubject')
            ->add('emailFromEmail')
            ->add('emailFromName')

            ->add('licenseTypeCondition', 'choice', [
                'choices' => [
                    'COMMERCIAL' => 'COMMERCIAL',
                    'EVALUATION' => 'EVALUATION'
                ]
            ])

            ->add('dateField', 'choice', [
                'choices' => [
                    'startDate' => 'startDate',
                    'endDate' => 'endDate'
                ]
            ])

            ->add('drillSchema', 'entity', [
                'class' => 'AppBundle:DrillSchema',
                'property' => 'name'
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DrillSchemaEvent'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_drillschemaevent';
    }
}
