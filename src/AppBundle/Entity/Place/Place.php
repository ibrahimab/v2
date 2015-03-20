<?php
namespace AppBundle\Entity\Place;

use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       Doctrine\Common\Collections\ArrayCollection;
use       Doctrine\ORM\Mapping as ORM;

/**
 * Place Entity
 *
 * @ORM\Table(name="plaats")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Place\PlaceRepository")
 */
class Place implements PlaceServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="plaats_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="hoortbij_plaats_id", type="integer")
     */
    private $siblingId;
    
    /**
     * @var Place
     *
     * @ORM\OneToOne(targetEntity="Place")
     * @ORM\JoinColumn(name="hoortbij_plaats_id", referencedColumnName="plaats_id")
     */
    private $sibling;

    /**
     * @var integer
     *
     * @ORM\Column(name="skigebied_id", type="integer")
     */
    private $regionId;
    
    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Region\Region")
     * @ORM\JoinColumn(name="skigebied_id", referencedColumnName="skigebied_id")
     */
    private $region;
    
    /**
     * @var AccommodationServiceEntityInterface[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Accommodation\Accommodation", mappedBy="place")
     */
    private $accommodations;
    
    /**
     * @var CountryServiceEntityInterface
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country\Country", inversedBy="places")
     * @ORM\JoinColumn(name="land_id", referencedColumnName="land_id")
     */
    private $country;

    /**
     * @var integer
     *
     * @ORM\Column(name="wzt", type="smallint")
     */
    private $season;

    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="altnaam", type="string", length=255)
     */
    private $alternativeName;

    /**
     * @var string
     *
     * @ORM\Column(name="altnaam_zichtbaar", type="string", length=255)
     */
    private $visibleAlternativeName;

    /**
     * @var array
     *
     * @ORM\Column(name="websites", type="simple_array")
     */
    private $websites;

    /**
     * @var string
     *
     * @ORM\Column(name="korteomschrijving", type="string", length=70)
     */
    private $shortDescription;

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
        $this->accommodations = new ArrayCollection();
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
    public function setSiblingId($siblingId)
    {
        $this->siblingId = $siblingId;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSiblingId()
    {
        return $this->siblingId;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setSibling($sibling)
    {
        $this->sibling = $sibling;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getSibling()
    {
        return $this->sibling;
    }

    /**
     * {@InheritDoc}
     */
    public function setRegionId($regionId)
    {
        $this->regionId = $regionId;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getRegionId()
    {
        return $this->regionId;
    }

    /**
     * {@InheritDoc}
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * {@InheritDoc}
     */
    public function setAccommodations($accommodations)
    {
        $this->accommodations = $accommodations;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getAccommodations()
    {
        return $this->accommodations;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setCountry($country)
    {
        $this->country = $country;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * {@InheritDoc}
     */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSeason()
    {
        return $this->season;
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
    public function setAlternativeName($alternativeName)
    {
        $this->alternativeName = $alternativeName;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getAlternativeName()
    {
        return $this->alternativeName;
    }

    /**
     * {@InheritDoc}
     */
    public function setVisibleAlternativeName($visibleAlternativeName)
    {
        $this->visibleAlternativeName = $visibleAlternativeName;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getVisibleAlternativeName()
    {
        return $this->visibleAlternativeName;
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