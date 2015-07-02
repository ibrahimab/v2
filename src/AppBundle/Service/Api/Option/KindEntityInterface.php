<?php
namespace AppBundle\Service\Api\Option;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
interface KindEntityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Setting travel insurance
     *
     * @param integer $travelInsurance
     * @return KindEntityInterface
     */
    public function setTravelInsurance($travelInsurance);

    /**
     * Getting travel insurance
     *
     * @return integer
     */
    public function getTravelInsurance();
}