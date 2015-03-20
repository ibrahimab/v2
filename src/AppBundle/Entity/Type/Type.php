<?php
namespace AppBundle\Entity\Type;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;
use       Doctrine\Common\Collections\ArrayCollection;

/**
 * Type
 *
 * @ORM\Table(name="type")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Type\TypeRepository")
 */
class Type implements TypeServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="accommodatie_id", type="integer")
     */
    private $accommodationId;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Accommodation\Accommodation", inversedBy="types")
     * @ORM\JoinColumn(name="accommodatie_id", referencedColumnName="accommodatie_id")
     */
    private $accommodation;

    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="korteomschrijving", type="string", length=70)
     */
    private $shortDescription;

    /**
     * @var integer
     *
     * @ORM\Column(name="voorraad", type="integer")
     */
    private $inventory;

    /**
     * @var array
     *
     * @ORM\Column(name="websites", type="simple_array")
     */
    private $websites;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=50)
     */
    private $code;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tonen", type="boolean")
     */
    private $display;

    /**
     * @var string
     *
     * @ORM\Column(name="omschrijving", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="gps_lat", type="string", length=12)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="gps_long", type="string", length=12)
     */
    private $longitude;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="optimaalaantalpersonen", type="smallint")
     */
    private $optimalResidents;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="maxaantalpersonen", type="smallint")
     */
    private $maxResidents;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="kwaliteit", type="smallint")
     */
    private $quality;
    
    /**
     * @var SurveyServiceEntityInterface
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Booking\Survey\Survey", mappedBy="type", fetch="EXTRA_LAZY")
     */
    private $surveys;
    
    /**
     * @var integer
     */
    private $surveyCount = 0;
    
    /**
     * @var integer
     */
    private $surveyAverageOverallRating = 0.0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="adddatetime", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="editdatetime", type="datetime")
     */
    private $updatedAt;


    public function __construct()
    {
        $this->surveys = new ArrayCollection();
    }

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
    public function setAccommodationId($accommodationId)
    {
        $this->accommodationId = $accommodationId;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getAccommodationId()
    {
        return $this->accommodationId;
    }

    /**
     * {@InheritDoc}
     */
    public function getAccommodation()
    {
        return $this->accommodation;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setAccommodation($accommodation)
    {
        $this->accommodation = $accommodation;
        
        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@InheritDoc}
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * {@InheritDoc}
     */
    public function setInventory($inventory)
    {
        $this->inventory = $inventory;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * {@InheritDoc}
     */
    public function setWebsites($websites)
    {
        $this->websites = $websites;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWebsites()
    {
        return $this->websites;
    }

    /**
     * {@InheritDoc}
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * {@InheritDoc}
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * {@InheritDoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@InheritDoc}
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * {@InheritDoc}
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setOptimalResidents($optimalResidents)
    {
        $this->optimalResidents = $optimalResidents;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getOptimalResidents()
    {
        return $this->optimalResidents;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setMaxResidents($maxResidents)
    {
        $this->maxResidents = $maxResidents;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getMaxResidents()
    {
        return $this->maxResidents;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getQuality()
    {
        return $this->quality;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setSurveys($surveys)
    {
        $this->surveys = $surveys;
        
        return $this;
    }
    
    public function getSurveys()
    {
        return $this->surveys;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setSurveyCount($surveyCount)
    {
        $this->surveyCount = $surveyCount;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getSurveyCount()
    {
        return $this->surveyCount;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setSurveyAverageOverallRating($surveyAverageOverallRating)
    {
        $this->surveyAverageOverallRating = $surveyAverageOverallRating;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getSurveyAverageOverallRating()
    {
        return round(floatval($this->surveyAverageOverallRating), 1);
    }

    /**
     * {@InheritDoc}
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
