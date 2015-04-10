<?php
namespace AppBundle\Doctrine\DBAL\Types;

use       AppBundle\Concern\FeatureConcern\FeatureConcernAccommodation;
use       Doctrine\DBAL\Platforms\AbstractPlatform;
use       Doctrine\DBAL\Types\SimpleArrayType;

class FeaturesAccommodationType extends SimpleArrayType
{
    const FEATURES_ACCOMMODATION = 'features_accommodation';
    
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (false === ($value instanceof FeatureConcernAccommodation)) {
            return null;
        }
        
        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }
    
    public function convertToPhpValue($value, AbstractPlatform $platform)
    {
        if (trim($value) === '' || false === (is_array($data = parent::convertToPhpValue($value, $platform)))) {
            return null;
        }
        
        return new FeatureConcernAccommodation($data);
    }
    
    public function getName()
    {
        return self::FEATURES_ACCOMMODATION;
    }
}