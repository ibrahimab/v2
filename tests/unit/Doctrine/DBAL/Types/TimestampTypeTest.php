<?php
namespace AppBundle\Tests\Unit\Doctrine\DBAL\Types;

// @TODO: autoload test folder
require_once dirname(__FILE__) . '/../Mocks/MockPlatform.php';

use       AppBundle\Doctrine\DBAL\Types\TimestampType;
use       AppBundle\Tests\Unit\Doctrine\DBAL\Mocks\MockPlatform;
use       Doctrine\DBAL\Types\Type;

class TimestampTypeTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var TimestampType
     */
    protected $timestampType;

    /**
     * @var \appTestDebugProjectContainer
     */
    protected $container;

    protected function _before()
    {
        if (false === Type::hasType(TimestampType::TIMESTAMP)) {
            Type::addType(TimestampType::TIMESTAMP, 'AppBundle\Doctrine\DBAL\Types\TimestampType');
        }

        $this->platform      = new MockPlatform();
        $this->timestampType = Type::getType(TimestampType::TIMESTAMP);
    }

    protected function _after()
    {
        $this->timestampType = null;
        $this->platform      = null;
    }

    public function testConvertToDatabaseValueWithDateTime()
    {
        $now    = new \DateTime('now');
        $result = $this->timestampType->convertToDatabaseValue($now, $this->platform);
        
        $this->assertEquals($now->getTimestamp(), $result);
    }

    public function testConvertToDatabaseValueWithTimestamp()
    {
        $now    = new \DateTime('now');
        $result = $this->timestampType->convertToDatabaseValue($now->getTimestamp(), $this->platform);
        
        $this->assertEquals($now->getTimestamp(), $result);
    }

    public function testConvertToPHPValue()
    {
        $now    = new \DateTime('now'); 
        $result = $this->timestampType->convertToPhpValue($now->getTimestamp(), $this->platform);
        
        $this->assertInstanceOf('\DateTime', $result);
        $this->assertEquals($now, $result);
    }
}