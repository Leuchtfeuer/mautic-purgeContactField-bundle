<?php

declare(strict_types=1);

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Form\Type;

use Mautic\LeadBundle\Model\FieldModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFieldType extends AbstractType
{
    public function __construct(
        private FieldModel $fieldModel,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $fields  = $this->fieldModel->getLeadFields();
        $choices = [];
        foreach ($fields as $field) {
            $key = $field->getLabel();
            if (!empty($field->isUniqueIdentifer())) {
                $key .= '*';
            }
            $choices[$key] = $field->getAlias();
        }

        $resolver->setDefaults([
            'choices'           => $choices,
            'required'          => false,
            'multiple'          => true,
            'expanded'          => false,
            'placeholder'       => 'mautic.core.form.chooseone',
            'label'             => 'lf.campaign.action.purgecontactfield.type.title',
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'fieldlist_choices';
    }
}
