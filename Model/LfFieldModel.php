<?php

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Model;

use Mautic\LeadBundle\Helper\FormFieldHelper;
use Mautic\LeadBundle\Model\FieldModel;

class LfFieldModel extends FieldModel
{
    public const EMPTY_TEXT = [
        'text',
        'email',
        'textarea',
        'timezone',
        'country',
        'locale',
        'region',
    ];

    public const EMPTY_NUMBER = [
        'number',
        'tel',
    ];

    public const EMPTY_LIST = [
        'select',
        'multiselect',
        'lookup',
    ];

    public const EMPTY_DATE = [
        'date',
        'datetime',
    ];

    public const EMPTY_TIME = [
        'time',
    ];

    public function getPurgeValueByAlias(string $alias)
    {
        $field = $this->getEntityByAlias($alias);
        if (!$field instanceof \Mautic\LeadBundle\Entity\LeadField) {
            new \Exception('Field not found');
        }

        return $this->getPurgeFieldValueByType($field->getType());
    }

    public function getPurgeFieldValueByType($type)
    {
        return $this->getPurgeFieldValues()[$type];
    }

    public function getPurgeFieldValues(): array
    {
        $fieldHelper = new FormFieldHelper();
        $fieldsType  = array_keys($fieldHelper->getTypes());
        $result      = [];
        foreach ($fieldsType as $fieldType) {
            switch ($fieldType) {
                case in_array($fieldType, self::EMPTY_TEXT):
                    $value = null;
                    break;
                case in_array($fieldType, self::EMPTY_NUMBER):
                    $value = 0;
                    break;
                case in_array($fieldType, self::EMPTY_LIST):
                    $value = [];
                    break;
                case in_array($fieldType, self::EMPTY_DATE):
//                    $value = new \DateTime();
                    $value = null;
                    break;
                case in_array($fieldType, self::EMPTY_TIME):
//                    $value = new \DateTime();
                    $value = null;
                    break;
                case 'boolean':
                    $value = false;
                    break;
                default:
                    $value = null;
                    break;
            }
            $result[$fieldType] = $value;
        }

        return $result;
    }
}
