<?php
namespace AppBundle\Service\Api\Legacy;

class Accommodation extends LegacyService
{
    /** @var integer */
    const API_METHOD_GET_INFO = 1;

    /**
     * @var integer
     */
    protected $endpoint = 6;

    /**
     * @param integer $typeId
     *
     * @return array
     */
    public function getInfo($typeId, $arrivalDate = null, $persons = null)
    {
        return $this->get(self::API_METHOD_GET_INFO, [

            'type_id'      => $typeId,
            'arrival_date' => $arrivalDate,
            'persons'      => $persons,
        ]);
    }
}