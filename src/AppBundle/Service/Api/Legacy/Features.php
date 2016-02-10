<?php
namespace AppBundle\Service\Api\Legacy;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class Features extends LegacyService
{
    /** @var integer */
    const API_METHOD_ALL = 1;

    /**
     * @var integer
     */
    protected $endpoint = 5;

    /**
     * @param array $typeIds
     *
     * @return array
     */
    public function all($typeId, $data)
    {
        return $this->get(self::API_METHOD_ALL, [

             'type_id' => $typeId,
             'data'    => $data,
        ]);
    }
}