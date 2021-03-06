<?php
namespace AppBundle\Service\Api\Legacy;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class StartingPrice extends LegacyService
{
	/** @var integer */
	const API_METHOD_GET_STARTING_PRICES = 1;

	/**
	 * @var integer
	 */
	protected $endpoint = 4;

	/**
	 * @param array $typeIds
	 *
	 * @return array
	 */
	public function getStartingPrices(array $typeIds)
	{
		return $this->json(self::API_METHOD_GET_STARTING_PRICES, [
             'type_id' => array_unique($typeIds),
        ]);
	}
}