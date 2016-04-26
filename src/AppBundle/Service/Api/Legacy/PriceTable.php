<?php
namespace AppBundle\Service\Api\Legacy;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class PriceTable extends LegacyService
{
    /** @var integer */
    const API_METHOD_GET_TABLE = 1;

    /**
     * @var integer
     */
    const API_METHOD_GET_TOTAL_PRICE = 2;

    /**
     * @var integer
     */
    protected $endpoint = 3;

    /**
     * @param integer $typeId
     * @param integer $seasonId
     *
     * @return array
     */
    public function getTable($typeId, $seasonId, $date, $numberOfPersons, $requestedFromUri)
    {
        return $this->get(self::API_METHOD_GET_TABLE, [

             'type_id'            => $typeId,
             'number_of_persons'  => $numberOfPersons,
             'date'               => $date,
             'requested_from_uri' => $requestedFromUri,
             'internal'           => $this->legacyCmsUserService->isLoggedIn(),

        ]);
    }

    /**
     * @param integer $typeId
     * @param string $seasonIdInQuery
     * @param integer $date
     * @param integer $numberOfPersons
     *
     * @return array
     */
    public function getTotalPrice($typeId, $seasonIdInQuery, $date, $numberOfPersons)
    {
        return $this->get(self::API_METHOD_GET_TOTAL_PRICE, [

             'type_id'           => $typeId,
             'season_id_inquery' => $seasonIdInQuery,
             'number_of_persons' => $numberOfPersons,
             'date'              => $date,
             'internal'          => $this->legacyCmsUserService->isLoggedIn(),

        ]);
    }
}
