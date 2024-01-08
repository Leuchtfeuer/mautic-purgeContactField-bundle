<?php

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PurgeContactFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('fields', ContactFieldType::class, [
            'label'      => 'lf.campaign.action.purgecontactfield.type.title',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'        => 'form-control',
                'tooltip'      => 'lf.campaign.action.purgecontactfield.type.desc',
            ],
            'multiple' => true,
            'expanded' => false,
            'required' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'purgecontactfield';
    }
}
