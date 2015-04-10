<?php
namespace AppBundle\Doctrine\DBAL\Types;

use       AppBundle\Concern\FeatureConcern\FeatureConcernType;
use       Doctrine\DBAL\Platforms\AbstractPlatform;
use       Doctrine\DBAL\Types\SimpleArrayType;

class FeaturesTypeType extends SimpleArrayType
{
    const FEATURES_TYPE = 'features_type';
    
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (false === ($value instanceof FeatureConcernType)) {
            return null;
        }
        
        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }
    
    public function convertToPhpValue($value, AbstractPlatform $platform)
    {
        if (trim($value) === '' || false === (is_array($data = parent::convertToPhpValue($value, $platform)))) {
            return null;
        }
        
        return new FeatureConcernType($data);
    }
    
    public function getName()
    {
        return self::FEATURES_TYPE;
    }
}