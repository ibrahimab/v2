<?php
namespace AppBundle\Entity;
use       Doctrine\ORM\EntityRepository;

/**
 * BaseRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class BaseRepository extends EntityRepository
{   
    public static function getOption(&$option, $default = null)
    {
        return isset($option) ? $option : $default;
    }
}