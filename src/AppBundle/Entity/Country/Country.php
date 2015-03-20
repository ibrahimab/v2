<?php
namespace AppBundle\Entity\Country;

use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       Doctrine\Common\Collections\ArrayCollection;
use       Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="land")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Country\CountryRepository")
 */
class Country implements CountryServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="land_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var PlaceServiceEntityInterface[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Place\Place", mappedBy="country")
     */
    private $places;

    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=50)
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
     * @ORM\Column(name="naam_en", type="string", length=50)
     */
    private $englishName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="naam_de", type="string", length=50)
     */
    private $germanName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tonen", type="boolean")
     */
    private $display;

    /**
     * @var string
     *
     * @ORM\Column(name="titel", type="string", length=70)
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="titel_en", type="string", length=70)
     */
    private $englishTitle;
    
    /**
     * @var string
     *
     * @ORM\Column(name="titel_de", type="string", length=70)
     */
    private $germanTitle;

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
     * @ORM\Column(name="kleurcode", type="smallint")
     */
    private $colourCode;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="begincode", type="string", length=2)
     */
    private $startCode;

    /**
     * @var array
     *
     * @ORM\Column(name="accommodatiecodes", type="simple_array")
     */
    private $accommodationCodes;

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
        $this->places = new ArrayCollection();
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
    public function setPlaces($places)
    {
        $this->places = $places;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getPlaces()
    {
        return $this->places;
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
    public function setLocaleNames($localeNames)
    {
        // normalize locales
        $localeNames = array_change_key_case($localeNames);
        
        $this->setName(isset($localeName['nl']) ? $localeNames['nl'] : '');
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
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setEnglishTitle($englishTitle)
    {
        $this->englishTitle = $englishTitle;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getEnglishTitle()
    {
        return $this->englishTitle;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setGermanTitle($germanTitle)
    {
        $this->germanTitle = $germanTitle;
        
        return $this;
    }
    
    public function getGermanTitle()
    {
        return $this->germanTitle;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setLocaleTitles($localeTitles)
    {
        // normalize locales
        $localeTitles = array_change_key_case($localeTitles);
        
        $this->setTitle(isset($localeTitles['nl']) ? $localeTitles['nl'] : '');
        $this->setEnglishTitle(isset($localeTitles['en']) ? $localeTitles['en'] : '');
        $this->setGermanTitle(isset($localeTitles['de']) ? $localeTitles['de'] : '');
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getLocaleTitle($locale)
    {
        $locale = strtolower($locale);
        switch ($locale) {
            
            case 'en':
                $localeTitle = $this->getEnglishTitle();
                break;
                
            case 'de':
                $localeTitle = $this->getGermanTitle();
                break;
            
            case 'nl':
            default:
                $localeTitle = $this->getTitle();
                break;
        }
        
        return $localeTitle;
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
                $localeShortDescription = $this->getDescription();
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
    public function setColourCode($colourCode)
    {
        $this->colourCode = $colourCode;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getColourCode()
    {
        return $this->colourCode;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setStartCode($startCode)
    {
        $this->startCode = $startCode;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getStartCode()
    {
        return $this->startCode;
    }

    /**
     * {@InheritDoc}
     */
    public function setAccommodationCodes($accommodationCodes)
    {
        $this->accommodationCodes = $accommodationCodes;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getAccommodationCodes()
    {
        return $this->accommodationCodes;
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
