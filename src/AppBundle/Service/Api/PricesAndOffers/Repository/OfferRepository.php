<?php
namespace AppBundle\Service\Api\PricesAndOffers\Repository;

use Doctrine\DBAL\Connection;
use PDO;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class OfferRepository implements OfferRepositoryInterface
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * Constructor
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db      = $db;
    }
    /**
     * @param array|null $typeIds
     *
     * @return array
     */
    public function getOffers($typeIds = null)
    {
        $query = 'SELECT DISTINCT type_id, seizoen_id AS season_id, aanbiedingskleur AS discount_color,
                                  korting_toon_als_aanbieding AS show_as_discount, toonexactekorting AS show_exact_discount,
                                  aanbieding_acc_percentage AS discount_percentage, aanbieding_acc_euro AS discount_euro
                  FROM   tarief
                  WHERE  korting_toon_als_aanbieding = 1
                  AND    beschikbaar = 1
                  AND    week >= UNIX_TIMESTAMP(CURDATE())
                  AND    (
                      bruto   > 0 OR
                      c_bruto > 0
                  )';

        if (is_array($typeIds)) {
            $query .= ' AND type_id IN (' . implode(', ', $typeIds) . ')';
        }

        $query .= ' ORDER BY week DESC';

        $statement = $this->db->prepare($query);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_column($results, null, 'type_id');
    }
}
