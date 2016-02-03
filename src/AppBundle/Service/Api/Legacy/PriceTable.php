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
    protected $endpoint = 3;

    /**
     * @param integer $typeId
     * @param integer $seasonId
     *
     * @return array
     */
    public function getTable($typeId, $seasonId)
    {
        $this->setParams([
            'method'     => self::API_METHOD_GET_TABLE,
             'type_id'   => $typeId,
             'season_id' => $seasonId,
        ]);

        $response = $this->client->get($this->uri, [
            'query' => $this->params,
        ]);

        return json_decode((string)$response->getBody(), true);
    }
}