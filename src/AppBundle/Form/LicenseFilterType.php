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

            ->add('sort_field', 'choice', [
                'choices' => [
                    'organisationName' => 'organisationName',
                    'addOnName' => 'addOnName',
                    'licenseType' => 'licenseType',
                    'startDate' => 'startDate',
                    'endDate' => 'endDate'
                ]
            ])

            ->add('sort_direction', 'choice', [
                'choices' => [
                    'ASC' => 'ASC',
                    'DESC' => 'DESC'
                ]
            ])

            ->add('limit', 'choice', [
                'choices' => [
                    '20' => '20',
                    '50' => '50',
                    '100' => '100',
                    '200' => '200'
                ]
            ]);
    }

    public function getName()
    {
        return 'app_license_filter';
    }
}