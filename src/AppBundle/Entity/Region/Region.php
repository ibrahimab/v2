<?php
namespace AppBundle\Entity\Region;

use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       Doctrine\Common\Collections\ArrayCollection;
use       Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table(name="skigebied")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Region\RegionRepository")
 */
class Region implements RegionServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="skigebied_id", type="integer")
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
     * @ORM\Column(name="wzt", type="smallint")
     */
    private $season;

    /**
     * @var array
     *
     * @ORM\Column(name="websites", type="simple_array")
     */
    private $websites;
    
    /**
     * This virtual field contains the types count within a certain region 
     * (composed by counting types of all the accommodations of all the places inside the Region)
     *
     * @var integer
     */
    private $typesCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="minhoogte", type="integer")
     */
    private $minimumAltitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxhoogte", type="integer")
     */
    private $maximumAltitude;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="kilometerpiste", type="integer")
     */
    private $totalSlopesDistance;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="aantalliften", type="integer")
     */
    private $totalElevators;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="aantalblauwepistes", type="integer")
     */
    private $totalBlueSlopes;

    /**
     * @var integer
     *
     * @ORM\Column(name="aantalrodepistes", type="integer")
     */
    private $totalRedSlopes;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="aantalzwartepistes", type="integer")
     */
    private $totalBlackSlopes;

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
    public function setMinimumAltitude($minimumAltitude)
    {
        $this->minimumAltitude = $minimumAltitude;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getMinimumAltitude()
    {
        return $this->minimumAltitude;
    }

    /**
     * {@InheritDoc}
     */
    public function setMaximumAltitude($maximumAltitude)
    {
        $this->maximumAltitude = $maximumAltitude;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getMaximumAltitude()
    {
        return $this->maximumAltitude;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setTotalElevators($totalElevators)
    {
        $this->totalElevators = $totalElevators;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getTotalElevators()
    {
        return $this->totalElevators;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setTotalSlopesDistance($totalSlopesDistance)
    {
        $this->totalSlopesDistance = $totalSlopesDistance;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getTotalSlopesDistance()
    {
        return $this->totalSlopesDistance;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setTotalBlueSlopes($totalBlueSlopes)
    {
        $this->totalBlueSlopes = $totalBlueSlopes;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getTotalBlueSlopes()
    {
        return $this->totalBlueSlopes;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setTotalRedSlopes($totalRedSlopes)
    {
        $this->totalRedSlopes = $totalRedSlopes;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getTotalRedSlopes()
    {
        return $this->totalRedSlopes;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setTotalBlackSlopes($totalBlackSlopes)
    {
        $this->totalBlackSlopes = $totalBlackSlopes;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getTotalBlackSlopes()
    {
        return $this->totalBlackSlopes;
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
