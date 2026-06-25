<?php

declare(strict_types=1);

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\EventListener;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\PendingEvent;
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

    public function addAction(CampaignBuilderEvent $event): void
    {
        if (!$this->config->isPublished()) {
            return;
        }
        $event->addAction(
            'campaign.purgecontactfields',
            [
                'label'          => 'lf.campaign.action.purgecontactfield.title',
                'description'    => 'lf.campaign.action.purgecontactfield.description',
                'formType'       => PurgeContactFieldType::class,
                'batchEventName' => LeuchtfeuerPurgeContactFieldEvents::ON_PURGE_CONTACT_FIELD,
            ]
        );
    }

    public function purgeContactField(PendingEvent $event): void
    {
        if (!$this->config->isPublished()) {
            $event->failAll('Plugin not published');

            return;
        }

        $fieldsPurged = $event->getEvent()->getProperties()['fields'] ?? [];

        foreach ($event->getPending() as $log) {
            $lead = $log->getLead();
            if (null === $lead) {
                $event->pass($log);
                continue;
            }

            try {
                $purgeFieldsValues = [];
                foreach ($fieldsPurged as $fieldAlias) {
                    $purgeFieldsValues[$fieldAlias] = $this->lfFieldModel->getPurgeValueByAlias($fieldAlias);
                }
                $this->leadModel->setFieldValues($lead, $purgeFieldsValues, true);
                $this->leadModel->saveEntity($lead);
                $event->pass($log);
            } catch (\Exception $e) {
                $event->fail($log, $e->getMessage());
            }
        }
    }
}
