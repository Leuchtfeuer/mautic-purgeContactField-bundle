<?php

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\EventListener;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\PendingEvent;
use Mautic\CampaignBundle\Membership\MembershipManager;
use Mautic\CampaignBundle\Model\CampaignModel;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Form\Type\PurgeContactFieldType;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\LeuchtfeuerPurgeContactFieldEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurgeContactFieldsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MembershipManager $membershipManager,
        private CampaignModel $campaignModel
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
        $event->addAction(
            'campaign.purgecontactfields',
            [
                'label'           => 'lf.campaign.action.purgecontactfield.title',
                'description'     => 'lf.campaign.action.purgecontactfield.description',
                'formType'        => PurgeContactFieldType::class,
                'batchEventName'  => LeuchtfeuerPurgeContactFieldEvents::ON_PURGE_CONTACT_FIELD,
            ]
        );
    }

    public function purgeContactField(PendingEvent $event)
    {
        dd($event);
    }
}
