<?php
namespace AppBundle\Tests\Unit\Doctrine\DBAL\Types;

// @TODO: autoload test folder
require_once dirname(__FILE__) . '/../Mocks/MockPlatform.php';

use       AppBundle\Concern\FeatureConcern\FeatureConcernAccommodation;
use       AppBundle\Doctrine\DBAL\Types\FeaturesAccommodationType;
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
    protected $featuresAccommodationType;
    
    /**
     * @var \appTestDebugProjectContainer
     */
    protected $container;

    protected function _before()
    {
        if (false === Type::hasType(FeaturesAccommodationType::FEATURES_ACCOMMODATION)) {
            Type::addType(FeaturesAccommodationType::FEATURES_ACCOMMODATION, 'AppBundle\Doctrine\DBAL\Types\FeaturesAccommodationType');
        }
        
        $this->platform                  = new MockPlatform();
        $this->featuresAccommodationType = Type::getType(FeaturesAccommodationType::FEATURES_ACCOMMODATION);
    }
    
    protected function _after()
    {
        $this->featuresAccommodationType = null;
        $this->platform                  = null;
    }
    
    public function testConvertToDatabaseValue()
    {
        $featuresWinter = new FeatureConcernAccommodation([
            FeatureConcernAccommodation::FEATURE_WINTER_CATERING, FeatureConcernAccommodation::FEATURE_WINTER_SKI_RUN, FeatureConcernAccommodation::FEATURE_WINTER_SPECIAL
        ]);
            
        $featuresSummer = new FeatureConcernAccommodation([
            FeatureConcernAccommodation::FEATURE_SUMMER_ACTIVE_IN_MOUNTAINS, FeatureConcernAccommodation::FEATURE_SUMMER_SWIMMING_POOL_PRIVATE, FeatureConcernAccommodation::FEATURE_SUMMER_CHALET_DETACHED
        ]);
        
        $resultWinter = $this->featuresAccommodationType->convertToDatabaseValue($featuresWinter, $this->platform);
        $resultSummer = $this->featuresAccommodationType->convertToDatabaseValue($featuresSummer, $this->platform);
        
        $this->assertEquals(implode(',', $featuresWinter->toArray()), $resultWinter);
        $this->assertEquals(implode(',', $featuresSummer->toArray()), $resultSummer);
    }

    public function testConvertToPHPValue()
    {
        $featuresStringWinter = FeatureConcernAccommodation::FEATURE_WINTER_CATERING . ',' . FeatureConcernAccommodation::FEATURE_WINTER_SKI_RUN . ',' . FeatureConcernAccommodation::FEATURE_WINTER_SPECIAL;
        $featuresStringSummer = FeatureConcernAccommodation::FEATURE_SUMMER_ACTIVE_IN_MOUNTAINS . ',' . FeatureConcernAccommodation::FEATURE_SUMMER_SWIMMING_POOL_PRIVATE . ',' . FeatureConcernAccommodation::FEATURE_SUMMER_CHALET_DETACHED;
                
        $featuresWinter = $this->featuresAccommodationType->convertToPhpValue($featuresStringWinter, $this->platform);
        $featuresSummer = $this->featuresAccommodationType->convertToPhpValue($featuresStringSummer, $this->platform);
        
        $this->assertInstanceOf('AppBundle\Concern\FeatureConcern\FeatureConcernAccommodation', $featuresWinter);
        $this->assertInstanceOf('AppBundle\Concern\FeatureConcern\FeatureConcernAccommodation', $featuresSummer);
        
        $this->assertTrue($featuresWinter->has(FeatureConcernAccommodation::FEATURE_WINTER_CATERING));
        $this->assertTrue($featuresWinter->has(FeatureConcernAccommodation::FEATURE_WINTER_SKI_RUN));
        $this->assertTrue($featuresWinter->has(FeatureConcernAccommodation::FEATURE_WINTER_SPECIAL));
        
        $this->assertTrue($featuresSummer->has(FeatureConcernAccommodation::FEATURE_SUMMER_ACTIVE_IN_MOUNTAINS));
        $this->assertTrue($featuresSummer->has(FeatureConcernAccommodation::FEATURE_SUMMER_SWIMMING_POOL_PRIVATE));
        $this->assertTrue($featuresSummer->has(FeatureConcernAccommodation::FEATURE_SUMMER_CHALET_DETACHED));
        
        $this->assertEquals([
            
            $featuresWinter->identifier(FeatureConcernAccommodation::FEATURE_SEASON_WINTER, FeatureConcernAccommodation::FEATURE_WINTER_CATERING), 
            $featuresWinter->identifier(FeatureConcernAccommodation::FEATURE_SEASON_WINTER, FeatureConcernAccommodation::FEATURE_WINTER_SKI_RUN), 
            $featuresWinter->identifier(FeatureConcernAccommodation::FEATURE_SEASON_WINTER, FeatureConcernAccommodation::FEATURE_WINTER_SPECIAL),
            
        ], $featuresWinter->get(FeatureConcernAccommodation::FEATURE_SEASON_WINTER));
        
        $this->assertEquals([
            
            $featuresSummer->identifier(FeatureConcernAccommodation::FEATURE_SEASON_SUMMER, FeatureConcernAccommodation::FEATURE_SUMMER_ACTIVE_IN_MOUNTAINS), 
            $featuresSummer->identifier(FeatureConcernAccommodation::FEATURE_SEASON_SUMMER, FeatureConcernAccommodation::FEATURE_SUMMER_SWIMMING_POOL_PRIVATE), 
            $featuresSummer->identifier(FeatureConcernAccommodation::FEATURE_SEASON_SUMMER, FeatureConcernAccommodation::FEATURE_SUMMER_CHALET_DETACHED),
            
        ], $featuresSummer->get(FeatureConcernAccommodation::FEATURE_SEASON_SUMMER));
    }
}