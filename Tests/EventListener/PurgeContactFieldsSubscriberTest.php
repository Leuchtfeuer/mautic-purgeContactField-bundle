<?php

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Tests\EventListener;

use Mautic\CampaignBundle\Entity\Campaign;
use Mautic\CampaignBundle\Entity\Event;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Model\LeadModel;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\EventListener\PurgeContactFieldsSubscriber;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Model\LfFieldModel;

class PurgeContactFieldsSubscriberTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->leadModel          = $this->createMock(LeadModel::class);
        $this->lfFieldModel       = $this->createMock(LfFieldModel::class);

        $this->subscriber = new PurgeContactFieldsSubscriber(
            $this->leadModel,
            $this->lfFieldModel,
        );
    }

    public function testPurgeContactFieldSubscriber(): void
    {
        $campaignMock = $this->createMock(Campaign::class);
        $campaignMock->method('getId')
            ->willReturn(1);
        $campaignMock->method('getName')
            ->willReturn('test');
        $campaignMock->method('getCreatedBy')
            ->willReturn('testCreated');
        $leadMock = $this->createMock(Lead::class);
        $leadMock->method('getId')
            ->willReturn(1);
        $leadMock->method('getLastName')
            ->willReturn('Parker');
        $leadMock->method('getFirstName')
            ->willReturn('Peter');
        $leadMock->method('getEmail')
            ->willReturn('peterparker@spiderman.com');
        $eventMock = $this->createMock(Event::class);
        $eventMock->method('getCampaign')
            ->willReturn($campaignMock);
        $eventMock->method('convertToArray')
            ->willReturn(
                [
                    'properties' => [
                        'fields' => [
                            'lastname',
                        ],
                    ],
                ]
            );

        $args = [
            'lead'            => $leadMock,
            'event'           => $eventMock,
            'eventDetails'    => [],
            'systemTriggered' => false,
            'eventSettings'   => [],
        ];
        $event = new CampaignExecutionEvent($args, true);
        $this->subscriber->purgeContactField($event);
        $this->assertCount(4, $event->getEvent());
        $this->assertEquals('lastname', $event->getEvent()['properties']['fields'][0]);
    }
}
