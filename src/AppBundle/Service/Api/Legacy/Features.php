<?php
namespace AppBundle\Service\Api\Legacy;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class Features extends LegacyService
{

    /**
     * @var integer: get all features for the front-end
     *
     */
    const API_METHOD_FRONTEND = 1;

    /**
     * @var integer: get all features for the back-end (used in CMS)
     */
    const API_METHOD_BACKEND = 2;

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
        return $this->get(self::API_METHOD_FRONTEND, [

             'type_id' => $typeId,
             'data'    => $data,
        ]);
    }

    /**
     * @param array $typeIds
     *
     * @return array
     */
    public function allBackEnd($typeId, $data)
    {
        return $this->get(self::API_METHOD_BACKEND, [

             'type_id' => $typeId,
             'data'    => $data,
        ]);
    }

}
