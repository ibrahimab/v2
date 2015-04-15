<?php
namespace AppBundle\Doctrine\DBAL\Types;

use       AppBundle\Concern\FeatureConcern\FeatureConcernAccommodation;
use       Doctrine\DBAL\Platforms\AbstractPlatform;
use       Doctrine\DBAL\Types\SimpleArrayType;

/**
 * FeaturesAccommodationType
 * 
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class FeaturesAccommodationType extends SimpleArrayType
{
    /**
     * Type name
     */
    const FEATURES_ACCOMMODATION = 'features_accommodation';
    
    /**
     * Converts the features accommodation concern to a comma separated value
     *
     * @param  mixed $value
     * @param  AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (false === ($value instanceof FeatureConcernAccommodation)) {
            return null;
        }
        
        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }
    
    /**
     * Converts a value loaded from the database to a FeatureConcernAccommodation
     *
     * @param int $value
     * @param AbstractPlatform $platform
     * @return FeatureConcernAccommodation
     */
    public function convertToPhpValue($value, AbstractPlatform $platform)
    {
        if (trim($value) === '' || false === (is_array($data = parent::convertToPhpValue($value, $platform)))) {
            return null;
        }
        
        return new FeatureConcernAccommodation($data);
    }
    
    /**
     * Get type name
     *
     * @return string
     */
    public function getName()
    {
        return self::FEATURES_ACCOMMODATION;
    }
}