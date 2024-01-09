<?php

namespace MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Tests\Model;

use Doctrine\ORM\EntityManagerInterface;
use Mautic\CoreBundle\Doctrine\Helper\ColumnSchemaHelper;
use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Mautic\CoreBundle\Helper\UserHelper;
use Mautic\CoreBundle\Security\Permissions\CorePermissions;
use Mautic\CoreBundle\Translation\Translator;
use Mautic\LeadBundle\Entity\LeadFieldRepository;
use Mautic\LeadBundle\Field\CustomFieldColumn;
use Mautic\LeadBundle\Field\Dispatcher\FieldSaveDispatcher;
use Mautic\LeadBundle\Field\FieldList;
use Mautic\LeadBundle\Field\FieldsWithUniqueIdentifier;
use Mautic\LeadBundle\Field\LeadFieldSaver;
use Mautic\LeadBundle\Model\ListModel;
use MauticPlugin\LeuchtfeuerPurgeContactFieldBundle\Model\LfFieldModel;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LfFieldModelTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        $columnSchemaHelper         = $this->createMock(ColumnSchemaHelper::class);
        $leadListModel              = $this->createMock(ListModel::class);
        $customFieldColumn          = $this->createMock(CustomFieldColumn::class);
        $fieldSaveDispatcher        = $this->createMock(FieldSaveDispatcher::class);
        $leadFieldRepository        = $this->createMock(LeadFieldRepository::class);

        $fieldsWithUniqueIdentifier = $this->createMock(FieldsWithUniqueIdentifier::class);
        $fieldList                  = $this->createMock(FieldList::class);
        $leadFieldSaver             = $this->createMock(LeadFieldSaver::class);

        $this->LfFieldModel = new LfFieldModel(
            $columnSchemaHelper,
            $leadListModel,
            $customFieldColumn,
            $fieldSaveDispatcher,
            $leadFieldRepository,
            $fieldsWithUniqueIdentifier,
            $fieldList,
            $leadFieldSaver,
            $this->createMock(EntityManagerInterface::class),
            $this->createMock(CorePermissions::class),
            $this->createMock(EventDispatcherInterface::class),
            $this->createMock(UrlGeneratorInterface::class),
            $this->createMock(Translator::class),
            $this->createMock(UserHelper::class),
            $this->createMock(LoggerInterface::class),
            $this->createMock(CoreParametersHelper::class)
        );
    }

    public function testGetPurgeFieldValues()
    {
        $result = $this->LfFieldModel->getPurgeFieldValues();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('datetime', $result);
        $this->assertArrayHasKey('number', $result);
        $this->assertSame(null, $result['text']);
        $this->assertSame(0, $result['number']);
        $this->assertSame(null, $result['datetime']);
    }

    public function testGetPurgeFieldValueByType()
    {
        $result = $this->LfFieldModel->getPurgeFieldValueByType('text');
        $this->assertSame(null, $result);
        $result = $this->LfFieldModel->getPurgeFieldValueByType('number');
        $this->assertSame(0, $result);
    }
}
