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
     * @ORM\Column(name="seonaam", type="string", length=100)
     */
    private $seoName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="seonaam_en", type="string", length=100)
     */
    private $englishSeoName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="seonaam_de", type="string", length=100)
     */
    private $germanSeoName;

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
     * @var string
     *
     * @ORM\Column(name="korteomschrijving", type="string", length=70)
     */
    private $shortDescription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="korteomschrijving_en", type="string", length=70)
     */
    private $englishShortDescription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="korteomschrijving_de", type="string", length=70)
     */
    private $germanShortDescription;

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
     * @var integer
     *
     * @ORM\Column(name="hoogte", type="integer")
     */
    private $altitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="afstandtotutrecht", type="integer")
     */
    private $distanceFromUtrecht;

    /**
     * @var array
     *
     * @ORM\Column(name="websites", type="simple_array")
     */
    private $websites;
    
    /**
     * This virtual field contains the types count within a certain place 
     *
     * @var integer
     */
    private $typesCount;
    
    /**
     * Virtual field that holds the average ratings for a place
     * 
     * @var integer
     */
    private $averageRatings = 0;
    
    /**
     * Virtual field that holds the ratings count for a place
     * 
     * @var integer
     */
    private $ratingsCount = 0;
    
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
    public function setEnglishName($englishName)
    {
        $this->englishName = $englishName;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getEnglishName()
    {
        return $this->englishName;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setGermanName($germanName)
    {
        $this->germanName = $germanName;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getGermanName()
    {
        return $this->germanName;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setLocaleNames($localeNames)
    {
        // normalize locales
        $localeNames = array_change_key_case($localeNames);
        
        $this->setName(isset($localeNames['nl']) ? $localeNames['nl'] : '');
        $this->setEnglishName(isset($localeNames['en']) ? $localeNames['en'] : '');
        $this->setGermanName(isset($localeNames['de']) ? $localeNames['de'] : '');
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getLocaleName($locale)
    {
        $locale = strtolower($locale);
        switch ($locale) {
            
            case 'en':
                $localeName = $this->getEnglishName();
                break;
                
            case 'de':
                $localeName = $this->getGermanName();
                break;
            
            case 'nl':
            default:
                $localeName = $this->getName();
                break;
        }
        
        return $localeName;
    }

    /**
     * {@InheritDoc}
     */
    public function setSeoName($seoName)
    {
        $this->seoName = $seoName;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSeoName()
    {
        return $this->seoName;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setEnglishSeoName($englishSeoName)
    {
        $this->englishSeoName = $englishSeoName;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getEnglishSeoName()
    {
        return $this->englishSeoName;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setGermanSeoName($germanSeoName)
    {
        $this->germanSeoName = $germanSeoName;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getGermanSeoName()
    {
        return $this->germanSeoName;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setLocaleSeoNames($localeSeoNames)
    {
        // normalize locales
        $localeSeoNames = array_change_key_case($localeSeoNames);
        
        $this->setSeoName(isset($localeSeoNames['nl']) ? $localeSeoNames['nl'] : '');
        $this->setEnglishSeoName(isset($localeSeoNames['en']) ? $localeSeoNames['en'] : '');
        $this->setGermanSeoName(isset($localeSeoNames['de']) ? $localeSeoNames['de'] : '');
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getLocaleSeoName($locale)
    {
        $locale = strtolower($locale);
        switch ($locale) {
            
            case 'en':
                $localeSeoName = $this->getEnglishSeoName();
                break;
                
            case 'de':
                $localeSeoName = $this->getGermanSeoName();
                break;
            
            case 'nl':
            default:
                $localeSeoName = $this->getSeoName();
                break;
        }
        
        return $localeSeoName;
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
    public function setEnglishShortDescription($englishShortDescription)
    {
        $this->englishShortDescription = $englishShortDescription;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getEnglishShortDescription()
    {
        return $this->englishShortDescription;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setGermanShortDescription($germanShortDescription)
    {
        $this->germanShortDescription = $germanShortDescription;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getGermanShortDescription()
    {
        return $this->germanShortDescription;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setLocaleShortDescriptions($localeShortDescriptions)
    {
        // normalize locales
        $localeShortDescriptions = array_change_key_case($localeShortDescriptions);
        
        $this->setShortDescription(isset($localeShortDescriptions['nl']) ? $localeShortDescriptions['nl'] : '');
        $this->setEnglishShortDescription(isset($localeShortDescriptions['en']) ? $localeShortDescriptions['en'] : '');
        $this->setGermanShortDescription(isset($localeShortDescriptions['de']) ? $localeShortDescriptions['de'] : '');
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getLocaleShortDescription($locale)
    {
        $locale = strtolower($locale);
        switch ($locale) {
            
            case 'en':
                $localeShortDescription = $this->getEnglishShortDescription();
                break;
                
            case 'de':
                $localeShortDescription = $this->getGermanShortDescription();
                break;
            
            case 'nl':
            default:
                $localeShortDescription = $this->getShortDescription();
                break;
        }
        
        return $localeShortDescription;
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
    public function setEnglishDescription($englishDescription)
    {
        $this->englishDescription = $englishDescription;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getEnglishDescription()
    {
        return $this->englishDescription;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setGermanDescription($germanDescription)
    {
        $this->germanDescription = $germanDescription;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getGermanDescription()
    {
        return $this->germanDescription;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setLocaleDescriptions($localeDescriptions)
    {
        // normalize locales
        $localeDescriptions = array_change_key_case($localeDescriptions);
        
        $this->setDescription(isset($localeDescriptions['nl']) ? $localeDescriptions['nl'] : '');
        $this->setEnglishDescription(isset($localeDescriptions['en']) ? $localeDescriptions['en'] : '');
        $this->setGermanDescription(isset($localeDescriptions['de']) ? $localeDescriptions['de'] : '');
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getLocaleDescription($locale)
    {
        $locale = strtolower($locale);
        switch ($locale) {
            
            case 'en':
                $localeDescription = $this->getEnglishDescription();
                break;
                
            case 'de':
                $localeDescription = $this->getGermanDescription();
                break;
            
            case 'nl':
            default:
                $localeDescription = $this->getDescription();
                break;
        }
        
        return $localeDescription;
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
    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * {@InheritDoc}
     */
    public function setDistanceFromUtrecht($distanceFromUtrecht)
    {
        $this->distanceFromUtrecht = $distanceFromUtrecht;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getDistanceFromUtrecht()
    {
        return $this->distanceFromUtrecht;
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
    public function setTypesCount($typesCount)
    {
        $this->typesCount = $typesCount;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getTypesCount()
    {
        return $this->typesCount;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setAverageRatings($averageRatings)
    {
        $this->averageRatings = $averageRatings;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getAverageRatings()
    {
        return $this->averageRatings;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setRatingsCount($ratingsCount)
    {
        $this->ratingsCount = $ratingsCount;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getRatingsCount()
    {
        return $this->ratingsCount;
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