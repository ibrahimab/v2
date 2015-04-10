<?php
namespace AppBundle\Tests\Unit\Doctrine\DBAL\Types;

// @TODO: autoload test folder
require_once dirname(__FILE__) . '/../Mocks/MockPlatform.php';

use       AppBundle\Concern\FeatureConcern\FeatureConcernAccommodation;
use       AppBundle\Doctrine\DBAL\Types\FeaturesAccommodationArrayType;
use       AppBundle\Tests\Unit\Doctrine\DBAL\Mocks\MockPlatform;
use       Doctrine\DBAL\Types\Type;

class FeaturesAccommodationArrayTypeTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var FeaturesAccommodationArrayType
     */
    protected $featuresAccommodationArrayType;
    
    /**
     * @var \appTestDebugProjectContainer
     */
    protected $container;

    protected function _before()
    {
        if (false === Type::hasType(FeaturesAccommodationArrayType::FEATURES_ACCOMMODATION_ARRAY)) {
            Type::addType(FeaturesAccommodationArrayType::FEATURES_ACCOMMODATION_ARRAY, 'AppBundle\Doctrine\DBAL\Types\FeaturesAccommodationArrayType');
        }
        
        $this->platform                       = new MockPlatform();
        $this->featuresAccommodationArrayType = Type::getType(FeaturesAccommodationArrayType::FEATURES_ACCOMMODATION_ARRAY);
    }
    
    protected function _after()
    {
        $this->featuresAccommodationArrayType = null;
        $this->platform                       = null;
    }
    
    public function testConvertToDatabaseValue()
    {
        $features = new FeatureConcernAccommodation([
            FeatureConcernAccommodation::FEATURE_CATERING, FeatureConcernAccommodation::FEATURE_SKI_RUN, FeatureConcernAccommodation::FEATURE_SPECIAL
        ]);
        
        $result = $this->featuresAccommodationArrayType->convertToDatabaseValue($features, $this->platform);
        
        $this->assertEquals(implode(',', $features->toArray()), $result);
    }

    public function testConvertToPHPValue()
    {
        $featuresString = FeatureConcernAccommodation::FEATURE_CATERING . ',' . FeatureConcernAccommodation::FEATURE_SKI_RUN . ',' . FeatureConcernAccommodation::FEATURE_SPECIAL;
        $features = $this->featuresAccommodationArrayType->convertToPhpValue($featuresString, $this->platform);
        
        $this->assertInstanceOf('AppBundle\Concern\FeatureConcern\FeatureConcernAccommodation', $features);
        $this->assertTrue($features->has(FeatureConcernAccommodation::FEATURE_CATERING));
        $this->assertTrue($features->has(FeatureConcernAccommodation::FEATURE_SKI_RUN));
        $this->assertTrue($features->has(FeatureConcernAccommodation::FEATURE_SPECIAL));
        
        $this->assertEquals([
            $features->identifier(FeatureConcernAccommodation::FEATURE_CATERING), $features->identifier(FeatureConcernAccommodation::FEATURE_SKI_RUN), $features->identifier(FeatureConcernAccommodation::FEATURE_SPECIAL),
        ], $features->get());
    }
}