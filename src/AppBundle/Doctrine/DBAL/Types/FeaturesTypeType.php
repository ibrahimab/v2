<?php
namespace AppBundle\Doctrine\DBAL\Types;

use       AppBundle\Concern\FeatureConcern\FeatureConcernType;
use       Doctrine\DBAL\Platforms\AbstractPlatform;
use       Doctrine\DBAL\Types\SimpleArrayType;

/**
 * FeaturesTypeType
 * 
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class FeaturesTypeType extends SimpleArrayType
{
    /**
     * Type name
     */
    const FEATURES_TYPE = 'features_type';

    /**
     * Converts the features type concern to a comma separated value
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (false === ($value instanceof FeatureConcernType)) {
            return null;
        }
        
        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }
    
    /**
     * Converts a value loaded from the database to a FeatureConcernType
     *
     * @param int $value
     * @param AbstractPlatform $platform
     * @return FeatureConcernType
     */
    public function convertToPhpValue($value, AbstractPlatform $platform)
    {
        if (trim($value) === '' || false === (is_array($data = parent::convertToPhpValue($value, $platform)))) {
            return null;
        }
        
        return new FeatureConcernType($data);
    }
    
    /**
     * Get type name
     *
     * @return string
     */
    public function getName()
    {
        return self::FEATURES_TYPE;
    }
}