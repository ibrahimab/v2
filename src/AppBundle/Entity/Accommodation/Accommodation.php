<?php
namespace AppBundle\Entity\Accommodation;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;
use       Doctrine\Common\Collections\ArrayCollection;

/**
 * Accommodation Entity
 *
 * @ORM\Table(name="accommodatie")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Accommodation\AccommodationRepository")
 */
class Accommodation implements AccommodationServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="accommodatie_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var TypeServiceEntityInterface[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Type\Type", mappedBy="accommodation")
     */
    private $types;

    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=255)
     */
    private $name;

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
     * @var string
     *
     * @ORM\Column(name="indeling", type="text")
     */
    private $layout;
    
    /**
     * @var string
     *
     * @ORM\Column(name="indeling_en", type="text")
     */
    private $englishLayout;
    
    /**
     * @var string
     *
     * @ORM\Column(name="indeling_de", type="text")
     */
    private $germanLayout;
    
    /**
     * @var PlaceServiceEntityInterface
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Place\Place", inversedBy="accommodations")
     * @ORM\JoinColumn(name="plaats_id", referencedColumnName="plaats_id")
     */
    private $place;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="soortaccommodatie", type="integer")
     */
    private $kind;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="tonen", type="boolean")
     */
    private $display;

    /**
     * @var array
     *
     * @ORM\Column(name="kenmerken", type="features_accommodation")
     */
    private $features;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="kwaliteit", type="smallint")
     */
    private $quality;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="zoekvolgorde", type="smallint")
     */
    private $searchOrder;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="weekendski", type="boolean")
     */
    private $weekendSki;

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
     * Kind identifiers for translations
     *
     * @var array
     */
    private static $kindIdentifiers = [

        self::KIND_CHALET           => 'chalet',
        self::KIND_APARTMENT        => 'apartment',
        self::KIND_HOTEL            => 'hotel',
        self::KIND_CHALET_APARTMENT => 'chalet-apartment',
        self::KIND_HOLIDAY_HOUSE    => 'holiday-house',
        self::KIND_VILLA            => 'villa',
        self::KIND_CASTLE           => 'castle',
        self::KIND_HOLIDAY_PARK     => 'holiday-park',
        self::KIND_AGRITURISMO      => 'agriturismo',
        self::KIND_DOMAIN           => 'domain',
        self::KIND_PENSION          => 'pension',
    ];


    public function __construct()
    {
        $this->types = new ArrayCollection();
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
    public function setTypes($types)
    {
        $this->types = $types;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getTypes()
    {
        return $this->types;
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
    public function setLocaleNames($localeNames)
    {
        // normalize locales
        $localeNames = array_change_key_case($localeNames);
        
        $this->setName(isset($localeNames['nl']) ? $localeNames['nl'] : '');
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getLocaleName($locale)
    {   
        return $this->getLocaleField('name', $locale, ['nl']);
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
        return $this->getLocaleField('shortDescription', $locale, ['nl', 'en', 'de']);
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
        return $this->getLocaleField('description', $locale, ['nl', 'en', 'de']);
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
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLayout()
    {
        return $this->layout;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setEnglishLayout($englishLayout)
    {
        $this->englishLayout = $englishLayout;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getEnglishLayout()
    {
        return $this->englishLayout;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setGermanLayout($germanLayout)
    {
        $this->germanLayout = $germanLayout;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getGermanLayout()
    {
        return $this->germanLayout;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setLocaleLayouts($localeLayouts)
    {
        // normalize locales
        $localeLayouts = array_change_key_case($localeLayouts);
        
        $this->setLayout(isset($localeLayouts['nl']) ? $localeLayouts['nl'] : '');
        $this->setEnglishLayout(isset($localeLayouts['en']) ? $localeLayouts['en'] : '');
        $this->setGermanLayout(isset($localeLayouts['de']) ? $localeLayouts['de'] : '');
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getLocaleLayout($locale)
    {   
        return $this->getLocaleField('layout', $locale, ['nl', 'en', 'de']);
    }

    /**
     * {@InheritDoc}
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getPlace()
    {
        return $this->place;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getKind()
    {
        return $this->kind;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getKindIdentifier()
    {
        return (isset(self::$kindIdentifiers[$this->getKind()]) ? self::$kindIdentifiers[$this->getKind()] : null);
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
    public function setFeatures($features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getFeatures()
    {
        return $this->features;
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
    public function setSearchOrder($searchOrder)
    {
        $this->searchOrder = $searchOrder;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getSearchOrder()
    {
        return $this->searchOrder;
    }

    /**
     * {@InheritDoc}
     */
    public function setWeekendSki($weekendSki)
    {
        $this->weekendSki = $weekendSki;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWeekendSki()
    {
        return $this->weekendSki;
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
    
    /**
     * {@InheritDoc}
     */
    public function getLocaleField($field, $locale, $allowedLocales)
    {
        $locale        = strtolower($locale);
        $allowedLocale = in_array($locale, $allowedLocales);
        
        switch (true) {
            
            case $allowedLocale && $locale === 'en':
                $localized = $this->{'getEnglish' . $field}();
                break;
                
            case $allowedLocale && $locale === 'de':
                $localized = $this->{'getGerman' . $field}();
                break;
            
            case $allowedLocale && $locale === 'nl':
            default:
                $localized = $this->{'get' . $field}();
                break;
        }
        
        return $localized;
    }
}
