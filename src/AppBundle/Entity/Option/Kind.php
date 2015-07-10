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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Option\Accommodation", inversedBy="kinds")
     * @ORM\JoinColumn(name="optie_soort_id", referencedColumnName="optie_soort_id")
     */
    private $accommodation;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="beschikbaar_wederverkoop", type="boolean")
     */
    private $availableResale;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="beschikbaar_directeklanten", type="boolean")
     */
    private $availableDirectClients;

    /**
     * @var int
     *
     * @ORM\Column(name="volgorde", type="smallint")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="naam_en", type="string", length=100)
     */
    private $englishName;

    /**
     * @var string
     *
     * @ORM\Column(name="naam_de", type="string", length=100)
     */
    private $germanName;

    /**
     * @var string
     *
     * @ORM\Column(name="omschrijving", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="omschrijving_en", type="text")
     */
    private $englishDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="omschrijving_de", type="text")
     */
    private $germanDescription;

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