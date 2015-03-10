<?php
namespace AppBundle\Entity\Accommodation;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceRepositoryInterface;

/**
 * AccommodationRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class AccommodationRepository extends BaseRepository implements AccommodationServiceRepositoryInterface
{
    /**
     * {@InheritDoc}
     */
    public function all($options = [])
    {
        $criteria = self::getOption($options['where'],  []);
        $order    = self::getOption($options['order'],  null);
        $limit    = self::getOption($options['limit'],  null);
        $offset   = self::getOption($options['offset'], null);
        
        return $this->findBy($criteria, $order, $limit, $offset);
    }
    
    /**
     * {@InheritDoc}
     */
    public function find($by)
    {
        return $this->findOneBy($by);
    }
}
