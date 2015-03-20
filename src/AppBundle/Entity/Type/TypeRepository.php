<?php
namespace AppBundle\Entity\Type;
use       AppBundle\Service\Api\Type\TypeServiceRepositoryInterface;
use       AppBundle\Entity\BaseRepository;

/**
 * TypeRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class TypeRepository extends BaseRepository implements TypeServiceRepositoryInterface
{
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