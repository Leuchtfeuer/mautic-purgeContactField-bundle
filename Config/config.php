<?php

return [
    'name'        => 'Purge Contact Fields by Leuchtfeuer',
    'description' => 'Adds Mautic campaign action to purge a contact field',
    'version'     => '1.0.0',
    'author'      => 'Leuchtfeuer Digital Marketing GmbH',
    'services'    => [
        'integrations' => [
            'mautic.integration.actionpurgecontactfield' => [
                'class' => \MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Integration\ActionPurgeContactFieldIntegration::class,
                'tags'  => [
                    'mautic.integration',
                    'mautic.basic_integration',
                ],
            ],
            'actionpurgecontactfield.integration.configuration' => [
                'class'  => \MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Integration\Support\ConfigSupport::class,
                'tags'   => [
                    'mautic.config_integration',
                ],
            ],
            'actionpurgecontactfield.integration.config' => [
                'class'     => \MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Integration\Config::class,
                'arguments' => [
                    'mautic.integrations.helper',
                ],
            ],
        ],
    ],
];
