<?php
namespace AppBundle\Entity\Accommodation;

use AppBundle\Entity\BaseRepository;
use AppBundle\Service\Api\Accommodation\AccommodationServiceRepositoryInterface;
use PDO;

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
     * @param array $accommodationIds
     */
    public function names($ids)
    {
        $connection = $this->getEntityManager()->getConnection();
        $locale     = $this->getLocale();

        $statement  = $connection->query("SELECT naam" . ($locale === 'nl' ? '' : ('_' . $locale)) . "
                                          FROM   accommodatie
                                          WHERE  accommodatie_id IN (" . implode(',', $ids) . ")");

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}