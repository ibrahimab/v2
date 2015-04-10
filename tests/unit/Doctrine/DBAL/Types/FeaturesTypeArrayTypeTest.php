<?php
namespace AppBundle\Tests\Unit\Doctrine\DBAL\Types;

// @TODO: autoload test folder
require_once dirname(__FILE__) . '/../Mocks/MockPlatform.php';

use       AppBundle\Concern\FeatureConcern\FeatureConcernType;
use       AppBundle\Doctrine\DBAL\Types\FeaturesTypeArrayType;
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
        if (false === Type::hasType(FeaturesTypeArrayType::FEATURES_TYPE_ARRAY)) {
            Type::addType(FeaturesTypeArrayType::FEATURES_TYPE_ARRAY, 'AppBundle\Doctrine\DBAL\Types\FeaturesTypeArrayType');
        }

        $this->platform                       = new MockPlatform();
        $this->featuresTypeArrayType          = Type::getType(FeaturesTypeArrayType::FEATURES_TYPE_ARRAY);
    }

    protected function _after()
    {
        $this->featuresTypeArrayType = null;
        $this->platform              = null;
    }

    public function testConvertToDatabaseValue()
    {
        $features = new FeatureConcernType([
            FeatureConcernType::FEATURE_CATERING, FeatureConcernType::FEATURE_SKI_RUN, FeatureConcernType::FEATURE_SPECIAL
        ]);

        $result = $this->featuresTypeArrayType->convertToDatabaseValue($features, $this->platform);

        $this->assertEquals(implode(',', $features->toArray()), $result);
    }

    public function testConvertToPHPValue()
    {
        $featuresString = FeatureConcernType::FEATURE_CATERING . ',' . FeatureConcernType::FEATURE_SKI_RUN . ',' . FeatureConcernType::FEATURE_SPECIAL;
        $features = $this->featuresTypeArrayType->convertToPhpValue($featuresString, $this->platform);

        $this->assertInstanceOf('AppBundle\Concern\FeatureConcern\FeatureConcernType', $features);
        $this->assertTrue($features->has(FeatureConcernType::FEATURE_CATERING));
        $this->assertTrue($features->has(FeatureConcernType::FEATURE_SKI_RUN));
        $this->assertTrue($features->has(FeatureConcernType::FEATURE_SPECIAL));

        $this->assertEquals([
            $features->identifier(FeatureConcernType::FEATURE_CATERING), $features->identifier(FeatureConcernType::FEATURE_SKI_RUN), $features->identifier(FeatureConcernType::FEATURE_SPECIAL),
        ], $features->get());
    }
}