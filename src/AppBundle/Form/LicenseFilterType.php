<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LicenseFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addon', 'choice', [
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'agile.estimation' => 'Planning Poker',
                    'jql.pro' => 'JQL Pro (Mongo Search)',
                    'simple.edit' => 'Simple Edit'
                ]
            ])
            ->add('license', 'choice', [
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'COMMERCIAL' => 'COMMERCIAL',
                    'EVALUATION' => 'EVALUATION'
                ]
            ])
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'app_license_filter';
    }
}