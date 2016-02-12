<?php
namespace AppBundle\Service\Api\Legacy;

class AdditionalCosts extends LegacyService
{
    /** @var integer */
    const API_METHOD_GET_BOOKING_DATA = 1;

    /** @var integer */
    const API_METHOD_GET_COMPLETE_CACHE = 2;

    /** @var integer */
    const API_METHOD_GET_COMPLETE_CACHE_PER_PERSONS = 3;

    /** @var integer */
    const API_METHOD_GET_COSTS = 4;

    /**
     * @var integer
     */
    protected $endpoint = 1;

    /**
     * @param integer $seasonType
     *
     * @return array
     */
    public function getCompleteCache($seasonType)
    {
        return $this->get(self::API_METHOD_GET_COMPLETE_CACHE, [
             'season_type' => $seasonType,
        ]);
    }

    /**
     * @param integer $seasonType
     * @param integer $persons
     *
     * @return array
     */
    public function getCompleteCachePerPersons($seasonType, $persons)
    {
        return $this->get(self::API_METHOD_GET_COMPLETE_CACHE_PER_PERSONS, [

            'season_type' => $seasonType,
            'persons'     => $persons,
        ]);
    }

    /**
     * @param integer $id
     * @param string $type
     *
     * @return array
     */
    public function getCosts($id, $type, $seasonId, $popup = false, $arrangement = false)
    {
        return $this->get(self::API_METHOD_GET_COSTS, [

            'id'          => $id,
            'type'        => $type,
            'season_id'   => $seasonId,
            'popup'       => $popup,
            'arrangement' => $arrangement,
        ]);
    }
}