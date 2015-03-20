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
    /**
     * Getting either the option passed in or the default
     * 
     */
    public static function getOption($options, $key, $default = null)
    {
        return isset($options[$key]) ? $options[$key] : $default;
    }
    
    /**
     * {@InheritDoc}
     */
    public function all($options = [])
    {
        $criteria = self::getOption($options, 'where',  []);
        $order    = self::getOption($options, 'order',  null);
        $limit    = self::getOption($options, 'limit',  null);
        $offset   = self::getOption($options, 'offset', null);
        
        return $this->findBy($criteria, $order, $limit, $offset);
    }
    
    /**
     * {@InheritDoc}
     */
    public function find($by = [])
    {
        return $this->findOneBy($by);
    }
}