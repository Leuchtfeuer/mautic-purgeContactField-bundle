<?php

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\EventListener;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Mautic\LeadBundle\Model\LeadModel;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Form\Type\PurgeContactFieldType;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Integration\Config;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\LeuchtfeuerPurgeContactFieldEvents;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Model\LfFieldModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurgeContactFieldsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LeadModel $leadModel,
        private LfFieldModel $lfFieldModel,
        private Config $config,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CampaignEvents::CAMPAIGN_ON_BUILD                          => ['addAction', 0],
            LeuchtfeuerPurgeContactFieldEvents::ON_PURGE_CONTACT_FIELD => ['purgeContactField', 0],
        ];
    }

    public function addAction(CampaignBuilderEvent $event)
    {
        if (!$this->config->isPublished()) {
            return;
        }
        $event->addAction(
            'campaign.purgecontactfields',
            [
                'label'           => 'lf.campaign.action.purgecontactfield.title',
                'description'     => 'lf.campaign.action.purgecontactfield.description',
                'formType'        => PurgeContactFieldType::class,
                'eventName'       => LeuchtfeuerPurgeContactFieldEvents::ON_PURGE_CONTACT_FIELD,
            ]
        );
    }

    public function purgeContactField(CampaignExecutionEvent $event): void
    {
        if (!$this->config->isPublished()) {
            return;
        }
        $fieldsPurged      = $event->getConfig()['fields'];
        $purgeFieldsValues = [];
        foreach ($fieldsPurged as $fieldPurged) {
            $purgeFieldsValues[$fieldPurged] = $this->lfFieldModel->getPurgeValueByAlias($fieldPurged);
        }
        $lead = $event->getLead();
        $this->leadModel->setFieldValues($lead, $purgeFieldsValues, true);
        $this->leadModel->saveEntity($lead);
    }
}
