<?php

declare(strict_types=1);

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

    public function getPurgeValueByAlias(string $alias): mixed
    {
        $field = $this->getEntityByAlias($alias);
        if (!$field instanceof \Mautic\LeadBundle\Entity\LeadField) {
            throw new \Exception('Field not found');
        }

        return $this->getPurgeFieldValueByType($field->getType());
    }

    public function getPurgeFieldValueByType(string $type): mixed
    {
        return $this->getPurgeFieldValues()[$type];
    }

    /**
     * @return array<string, mixed>
     */
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
                    $value = null;
                    break;
                case in_array($fieldType, self::EMPTY_TIME):
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
