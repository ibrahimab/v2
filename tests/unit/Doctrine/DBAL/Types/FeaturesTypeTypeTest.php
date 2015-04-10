<?php
namespace AppBundle\Tests\Unit\Doctrine\DBAL\Types;

// @TODO: autoload test folder
require_once dirname(__FILE__) . '/../Mocks/MockPlatform.php';

use       AppBundle\Concern\FeatureConcern\FeatureConcernType;
use       AppBundle\Doctrine\DBAL\Types\FeaturesTypeType;
use       AppBundle\Tests\Unit\Doctrine\DBAL\Mocks\MockPlatform;
use       Doctrine\DBAL\Types\Type;

class FeaturesArrayTypeTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var FeaturesTypeArrayType
     */
    protected $featuresTypeArrayType;

    /**
     * @var \appTestDebugProjectContainer
     */
    protected $container;

    protected function _before()
    {
        if (false === Type::hasType(FeaturesTypeType::FEATURES_TYPE)) {
            Type::addType(FeaturesTypeType::FEATURES_TYPE, 'AppBundle\Doctrine\DBAL\Types\FeaturesTypeType');
        }

        $this->platform         = new MockPlatform();
        $this->featuresTypeType = Type::getType(FeaturesTypeType::FEATURES_TYPE);
    }

    protected function _after()
    {
        $this->featuresTypeArrayType = null;
        $this->platform              = null;
    }

    public function testConvertToDatabaseValue()
    {
        $featuresWinter = new FeatureConcernType([
            
            FeatureConcernType::FEATURE_WINTER_CATERING, 
            FeatureConcernType::FEATURE_WINTER_SKI_RUN, 
            FeatureConcernType::FEATURE_WINTER_SPECIAL
        ]);
            
        $featuresSummer = new FeatureConcernType([
            
            FeatureConcernType::FEATURE_SUMMER_CHALET_DETACHED_MULTIPLE, 
            FeatureConcernType::FEATURE_SUMMER_NOT_GROUND_LEVEL, 
            FeatureConcernType::FEATURE_SUMMER_BARBECUE_PRIVATE
        ]);

        $resultWinter = $this->featuresTypeType->convertToDatabaseValue($featuresWinter, $this->platform);
        $resultSummer = $this->featuresTypeType->convertToDatabaseValue($featuresSummer, $this->platform);

        $this->assertEquals(implode(',', $featuresWinter->toArray()), $resultWinter);
        $this->assertEquals(implode(',', $featuresSummer->toArray()), $resultSummer);
    }

    public function testConvertToPHPValue()
    {
        $featuresStringWinter = FeatureConcernType::FEATURE_WINTER_CATERING . ',' . FeatureConcernType::FEATURE_WINTER_SKI_RUN . ',' . FeatureConcernType::FEATURE_WINTER_SPECIAL;
        $featuresStringSummer = FeatureConcernType::FEATURE_SUMMER_CHALET_DETACHED_MULTIPLE . ',' . FeatureConcernType::FEATURE_SUMMER_NOT_GROUND_LEVEL . ',' . FeatureConcernType::FEATURE_SUMMER_BARBECUE_PRIVATE;
                
        $featuresWinter = $this->featuresTypeType->convertToPhpValue($featuresStringWinter, $this->platform);
        $featuresSummer = $this->featuresTypeType->convertToPhpValue($featuresStringSummer, $this->platform);

        $this->assertInstanceOf('AppBundle\Concern\FeatureConcern\FeatureConcernType', $featuresWinter);
        $this->assertInstanceOf('AppBundle\Concern\FeatureConcern\FeatureConcernType', $featuresSummer);
        
        $this->assertTrue($featuresWinter->has(FeatureConcernType::FEATURE_WINTER_CATERING));
        $this->assertTrue($featuresWinter->has(FeatureConcernType::FEATURE_WINTER_SKI_RUN));
        $this->assertTrue($featuresWinter->has(FeatureConcernType::FEATURE_WINTER_SPECIAL));
        
        $this->assertTrue($featuresSummer->has(FeatureConcernType::FEATURE_SUMMER_CHALET_DETACHED_MULTIPLE));
        $this->assertTrue($featuresSummer->has(FeatureConcernType::FEATURE_SUMMER_NOT_GROUND_LEVEL));
        $this->assertTrue($featuresSummer->has(FeatureConcernType::FEATURE_SUMMER_BARBECUE_PRIVATE));

        $this->assertEquals([
            
            $featuresWinter->identifier(FeatureConcernType::FEATURE_SEASON_WINTER, FeatureConcernType::FEATURE_WINTER_CATERING), 
            $featuresWinter->identifier(FeatureConcernType::FEATURE_SEASON_WINTER, FeatureConcernType::FEATURE_WINTER_SKI_RUN), 
            $featuresWinter->identifier(FeatureConcernType::FEATURE_SEASON_WINTER, FeatureConcernType::FEATURE_WINTER_SPECIAL),
            
        ], $featuresWinter->get(FeatureConcernType::FEATURE_SEASON_WINTER));
        
        $this->assertEquals([
            
            $featuresSummer->identifier(FeatureConcernType::FEATURE_SEASON_SUMMER, FeatureConcernType::FEATURE_SUMMER_CHALET_DETACHED_MULTIPLE), 
            $featuresSummer->identifier(FeatureConcernType::FEATURE_SEASON_SUMMER, FeatureConcernType::FEATURE_SUMMER_NOT_GROUND_LEVEL), 
            $featuresSummer->identifier(FeatureConcernType::FEATURE_SEASON_SUMMER, FeatureConcernType::FEATURE_SUMMER_BARBECUE_PRIVATE),
            
        ], $featuresSummer->get(FeatureConcernType::FEATURE_SEASON_SUMMER));
    }
}