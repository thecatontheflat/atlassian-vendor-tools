<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LicenseFilterType extends AbstractType
{
    private $addonChoices;

    public function __construct($addonChoices)
    {
        $this->addonChoices = $addonChoices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addonKey', 'choice', [
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->addonChoices
            ])

            ->add('licenseType', 'choice', [
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'ACADEMIC' => 'ACADEMIC',
                    'COMMERCIAL' => 'COMMERCIAL',
                    'COMMUNITY' => 'COMMUNITY',
                    'EVALUATION' => 'EVALUATION',
                    'OPEN_SOURCE' => 'OPEN_SOURCE',
                    'STARTER' => 'STARTER'
                ]
            ])

            ->add('sort_field', 'choice', [
                'choices' => [
                    'organisationName' => 'Organization',
                    'addOnName' => 'Addon',
                    'licenseType' => 'Type',
                    'startDate' => 'Start date',
                    'endDate' => 'End date'
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