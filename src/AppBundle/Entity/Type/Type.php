<?php
namespace AppBundle\Entity\Type;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;
use       Doctrine\Common\Collections\ArrayCollection;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.1
 */
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Booking\Booking", mappedBy="type")
     */
    private $bookings;

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
     * @var boolean
     *
     * @ORM\Column(name="tonenzoekformulier", type="boolean")
     */
    private $displaySearch;

    /**
     * @var array
     *
     * @ORM\Column(name="kenmerken", type="simple_array")
     */
    private $features;

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
     * @ORM\Column(name="slaapkamers", type="smallint")
     */
    private $bedrooms;

    /**
     * @var string
     *
     * @ORM\Column(name="slaapkamersextra", type="string", length=255)
     */
    private $bedroomsExtra;

    /**
     * @var string
     *
     * @ORM\Column(name="slaapkamersextra_en", type="string", length=255)
     */
    private $englishBedroomsExtra;

    /**
     * @var string
     *
     * @ORM\Column(name="slaapkamersextra_de", type="string", length=255)
     */
    private $germanBedroomsExtra;

    /**
     * @var integer
     *
     * @ORM\Column(name="badkamers", type="smallint")
     */
    private $bathrooms;

    /**
     * @var string
     *
     * @ORM\Column(name="badkamersextra", type="string", length=255)
     */
    private $bathroomsExtra;

    /**
     * @var string
     *
     * @ORM\Column(name="badkamersextra_en", type="string", length=255)
     */
    private $englishBathroomsExtra;

    /**
     * @var string
     *
     * @ORM\Column(name="badkamersextra_de", type="string", length=255)
     */
    private $germanBathroomsExtra;

    /**
     * @var integer
     *
     * @ORM\Column(name="oppervlakte", type="integer")
     */
    private $surface;

    /**
     * @var string
     *
     * @ORM\Column(name="oppervlakteextra", type="string", length=255)
     */
    private $surfaceExtra;

    /**
     * @var string
     *
     * @ORM\Column(name="oppervlakteextra_en", type="string", length=255)
     */
    private $englishSurfaceExtra;

    /**
     * @var string
     *
     * @ORM\Column(name="oppervlakteextra_de", type="string", length=255)
     */
    private $germanSurfaceExtra;

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
        return $this->getLocaleField('name', $locale, ['nl', 'en', 'de']);
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
    public function setDisplaySearch($displaySearch)
    {
        $this->displaySearch = $displaySearch;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getDisplaySearch()
    {
        return $this->displaySearch;
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
    public function setBedrooms($bedrooms)
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getBedrooms()
    {
        return $this->bedrooms;
    }

    /**
     * {@InheritDoc}
     */
    public function setBedroomsExtra($bedroomsExtra)
    {
        $this->bedroomsExtra = $bedroomsExtra;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getBedroomsExtra()
    {
        return $this->bedroomsExtra;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishBedroomsExtra($englishBedroomsExtra)
    {
        $this->englishBedroomsExtra = $englishBedroomsExtra;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishBedroomsExtra()
    {
        return $this->englishBedroomsExtra;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanBedroomsExtra($germanBedroomsExtra)
    {
        $this->germanBedroomsExtra = $germanBedroomsExtra;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanBedroomsExtra()
    {
        return $this->germanBedroomsExtra;
    }

    /**
     * {@InheritDoc}
     */
    public function setLocaleBedroomsExtras($localeBedroomsExtras)
    {
        // normalize locales
        $localeBedroomsExtras = array_change_key_case($localeBedroomsExtras);

        $this->setBedroomsExtra(isset($localeBedroomsExtras['nl']) ? $localeBedroomsExtras['nl'] : '');
        $this->setEnglishBedroomsExtra(isset($localeBedroomsExtras['en']) ? $localeBedroomsExtras['en'] : '');
        $this->setGermanBedroomsExtra(isset($localeBedroomsExtras['de']) ? $localeBedroomsExtras['de'] : '');

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleBedroomsExtra($locale)
    {
        return $this->getLocaleField('bedroomsextra', $locale, ['nl', 'en', 'de']);
    }

    /**
     * {@InheritDoc}
     */
    public function setBathrooms($bathrooms)
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getBathrooms()
    {
        return $this->bathrooms;
    }

    /**
     * {@InheritDoc}
     */
    public function setBathroomsExtra($bathroomsExtra)
    {
        $this->bathroomsExtra = $bathroomsExtra;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getBathroomsExtra()
    {
        return $this->bathroomsExtra;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishBathroomsExtra($englishBathroomsExtra)
    {
        $this->englishBathroomsExtra = $englishBathroomsExtra;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishBathroomsExtra()
    {
        return $this->englishBathroomsExtra;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanBathroomsExtra($germanBathroomsExtra)
    {
        $this->germanBathroomsExtra = $germanBathroomsExtra;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanBathroomsExtra()
    {
        return $this->germanBathroomsExtra;
    }

    /**
     * {@InheritDoc}
     */
    public function setLocaleBathroomsExtras($localeBathroomsExtras)
    {
        // normalize locales
        $localeBathroomsExtras = array_change_key_case($localeBathroomsExtras);

        $this->setBathroomsExtra(isset($localeBathroomsExtras['nl']) ? $localeBathroomsExtras['nl'] : '');
        $this->setEnglishBathroomsExtra(isset($localeBedroomsExtras['en']) ? $localeBathroomsExtras['en'] : '');
        $this->setGermanBathroomsExtra(isset($localeBathroomsExtras['de']) ? $localeBathroomsExtras['de'] : '');

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleBathroomsExtra($locale)
    {
        return $this->getLocaleField('bathroomsextra', $locale, ['nl', 'en', 'de']);
    }

    /**
     * {@InheritDoc}
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * {@InheritDoc}
     */
    public function setSurfaceExtra($surfaceExtra)
    {
        $this->surfaceExtra = $surfaceExtra;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSurfaceExtra()
    {
        return $this->surfaceExtra;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishSurfaceExtra($englishSurfaceExtra)
    {
        $this->englishSurfaceExtra = $englishSurfaceExtra;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishSurfaceExtra()
    {
        return $this->englishSurfaceExtra;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanSurfaceExtra($germanSurfaceExtra)
    {
        $this->germanSurfaceExtra = $germanSurfaceExtra;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanSurfaceExtra()
    {
        return $this->germanSurfaceExtra;
    }

    /**
     * {@InheritDoc}
     */
    public function setLocaleSurfaceExtras($localeSurfaceExtras)
    {
        // normalize locales
        $localeSurfaceExtras = array_change_key_case($localeSurfaceExtras);

        $this->setSurfaceExtra(isset($localeSurfaceExtras['nl']) ? $localeSurfaceExtras['nl'] : '');
        $this->setEnglishSurfaceExtra(isset($localeSurfaceExtras['en']) ? $localeSurfaceExtras['en'] : '');
        $this->setGermanSurfaceExtra(isset($localeSurfaceExtras['de']) ? $localeSurfaceExtras['de'] : '');

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleSurfaceExtra($locale)
    {
        return $this->getLocaleField('surfaceExtra', $locale, ['nl', 'en', 'de']);
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
