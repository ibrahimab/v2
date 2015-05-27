<?php
namespace AppBundle\Doctrine\DBAL\Types;

use       Doctrine\DBAL\Types\Type;
use       Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * TimestampType
 * 
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class TimestampType extends Type
{
    /**
     * Type name
     */
    const TIMESTAMP = 'timestamp';

    /**
     * Getting SQL declaration
     *
     * @param  array $fieldDeclaration
     * @param  AbstractPlatform $platform
     * @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * Converts the timestamp to a value for database insertion
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return integer
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {   
        if ($value instanceof \DateTime) {
            return $value->getTimestamp();
        }
        
        return intval($value);
    }

    /**
     * Converts a value loaded from the database to a DateTime instance
     *
     * @param int $value
     * @param AbstractPlatform $platform
     * @return \DateTime
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $datetime = new \DateTime();
        $datetime->setTimestamp($value);
        
        return $datetime;
    }

    /**
     * Get Type name
     *
     * @return string
     */
    public function getName()
    {
        return self::TIMESTAMP;
    }
}