<?php
namespace AppBundle\Service\Api\Season;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
interface SeasonServiceRepositoryInterface
{
    /**
     * Getting policy costs
     *
     * @return float
     */
    public function getInsurancesPolicyCosts();
}
