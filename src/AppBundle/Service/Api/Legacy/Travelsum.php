<?php
namespace AppBundle\Service\Api\Legacy;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class Travelsum extends LegacyService
{
    /** @var integer */
    const API_METHOD_TABLE = 1;

    /**
     * @var integer
     */
    protected $endpoint = 7;

    /**
     * @param integer $bookingId
     * @param integer $typeId
     * @param integer $arrivalDate
     * @param integer $persons
     *
     * @return array
     */
    public function table($bookingId, $typeId, $arrivalDate, $persons)
    {
        return $this->get(self::API_METHOD_TABLE, [

             'booking_id'   => $bookingId,
             'type_id'      => $typeId,
             'arrival_date' => $arrivalDate,
             'persons'      => $persons,
        ]);
    }
}