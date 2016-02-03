<?php
namespace AppBundle\Service\Api\Legacy;

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
    protected $endpoint = 3;

    /**
     * @param integer $typeId
     * @param integer $seasonId
     *
     * @return array
     */
    public function getTable($typeId, $seasonId)
    {
        $this->method = self::API_METHOD_GET_TABLE;

        $response = $this->client->get($this->uri, [

            'query' => [

                'type_id'   => $typeId,
                'season_id' => $seasonId,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}