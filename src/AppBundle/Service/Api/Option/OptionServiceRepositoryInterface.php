<?php
namespace AppBundle\Service\Api\Option;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
interface OptionServiceRepositoryInterface
{
    /**
     * @return string
     */
    public function getTravelInsurancesDescription();
}