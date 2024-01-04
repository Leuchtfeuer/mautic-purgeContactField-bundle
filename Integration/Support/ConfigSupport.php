<?php

declare(strict_types=1);

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Integration\Support;

use Mautic\IntegrationsBundle\Integration\DefaultConfigFormTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormInterface;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Integration\ActionPurgeContactFieldIntegration;

class ConfigSupport extends ActionPurgeContactFieldIntegration implements ConfigFormInterface
{
    use DefaultConfigFormTrait;
}
