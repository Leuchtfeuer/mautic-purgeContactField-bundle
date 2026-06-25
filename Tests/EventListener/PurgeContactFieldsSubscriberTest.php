<?php

declare(strict_types=1);

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Tests\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Mautic\CampaignBundle\Entity\Event;
use Mautic\CampaignBundle\Entity\LeadEventLog;
use Mautic\CampaignBundle\Event\PendingEvent;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Model\LeadModel;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\EventListener\PurgeContactFieldsSubscriber;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Integration\Config;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Model\LfFieldModel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PurgeContactFieldsSubscriberTest extends TestCase
{
    private LeadModel&MockObject $leadModel;
    private LfFieldModel&MockObject $lfFieldModel;
    private Config&MockObject $config;
    private PurgeContactFieldsSubscriber $subscriber;

    protected function setUp(): void
    {
        parent::setUp();

        $this->leadModel    = $this->createMock(LeadModel::class);
        $this->lfFieldModel = $this->createMock(LfFieldModel::class);
        $this->config       = $this->createMock(Config::class);

        $this->subscriber = new PurgeContactFieldsSubscriber(
            $this->leadModel,
            $this->lfFieldModel,
            $this->config
        );
    }

    public function testPurgeContactFieldSubscriber(): void
    {
        $this->config->method('isPublished')->willReturn(true);

        $lead = $this->createMock(Lead::class);

        $log = $this->createMock(LeadEventLog::class);
        $log->method('getLead')->willReturn($lead);

        $campaignEvent = $this->createMock(Event::class);
        $campaignEvent->method('getProperties')->willReturn([
            'fields' => ['lastname'],
        ]);

        $pendingEvent = $this->createMock(PendingEvent::class);
        $pendingEvent->method('getEvent')->willReturn($campaignEvent);
        $pendingEvent->method('getPending')->willReturn(new ArrayCollection([$log]));

        $this->lfFieldModel->expects(self::once())
            ->method('getPurgeValueByAlias')
            ->with('lastname')
            ->willReturn(null);

        $this->leadModel->expects(self::once())
            ->method('setFieldValues')
            ->with($lead, ['lastname' => null], true);

        $this->leadModel->expects(self::once())
            ->method('saveEntity')
            ->with($lead);

        $pendingEvent->expects(self::once())->method('pass')->with($log);

        $this->subscriber->purgeContactField($pendingEvent);
    }
}
