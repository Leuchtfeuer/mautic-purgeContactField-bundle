<?php

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Integration;

use Mautic\IntegrationsBundle\Integration\BasicIntegration;
use Mautic\IntegrationsBundle\Integration\ConfigurationTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\BasicInterface;

class ActionPurgeContactFieldIntegration extends BasicIntegration implements BasicInterface
{
    use ConfigurationTrait;

    public const INTEGRATION_NAME = 'actionpurgecontactfield';
    public const DISPLAY_NAME     = 'Purge Contact Field';

    public function getIcon(): string
    {
        return 'plugins/LeuchtfeuerPurgeContactFieldBundle/Assets/img/icon.png';
    }

    public function getName(): string
    {
        return self::INTEGRATION_NAME;
    }

    public function getDisplayName(): string
    {
        return self::DISPLAY_NAME;
    }
}
