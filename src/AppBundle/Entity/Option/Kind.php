<?php
namespace AppBundle\Entity\Option;
use       AppBundle\Service\Api\Option\KindEntityInterface;
use       Doctrine\ORM\Mapping as ORM;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
/**
 * Option kind
 *
 * @ORM\Table(name="optie_soort")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Option\OptionRepository")
 */
class Kind implements KindEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="optie_soort_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="reisverzekering", type="smallint")
     */
    private $travelInsurance;


    /**
     * {@InheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@InheritDoc}
     */
    public function setTravelInsurance($travelInsurance)
    {
        $this->travelInsurance = $travelInsurance;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getTravelInsurance()
    {
        return $this->travelInsurance;
    }
}