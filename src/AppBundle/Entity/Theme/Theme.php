<?php
namespace AppBundle\Entity\Theme;
use       AppBundle\Service\Api\Theme\ThemeServiceEntityInterface;
use       Doctrine\ORM\Mapping AS ORM;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
/**
 * Theme
 *
 * @ORM\Table(name="thema")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Theme\ThemeRepository")
 */
class Theme implements ThemeServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="thema_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="wzt", type="smallint")
     */
    private $season;

    /**
     * @var integer
     *
     * @ORM\Column(name="actief", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="uitgebreidzoeken_url", type="string", length=30)
     */
    private $filters;

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
     * @ORM\Column(name="toelichting", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="toelichting_en", type="text")
     */
    private $englishDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="toelichting_de", type="text")
     */
    private $germanDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=100)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="url_en", type="string", length=100)
     */
    private $englishUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="url_de", type="string", length=100)
     */
    private $germanUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="externeurl", type="string", length=100)
     */
    private $externalUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="externeurl_en", type="string", length=100)
     */
    private $englishExternalUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="externeurl_de", type="string", length=100)
     */
    private $germanExternalUrl;


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
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * {@InheritDoc}
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getFilters()
    {
        return $this->filters;
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
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishUrl($englishUrl)
    {
        $this->englishUrl = $englishUrl;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishUrl()
    {
        return $this->englishUrl;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanUrl($germanUrl)
    {
        $this->germanUrl = $germanUrl;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanUrl()
    {
        return $this->germanUrl;
    }

    /**
     * {@InheritDoc}
     */
    public function setLocaleUrls($localeUrls)
    {
        // normalize locales
        $localeUrls = array_change_key_case($localeUrls);

        $this->setUrl(isset($localeUrls['nl']) ? $localeUrls['nl'] : '');
        $this->setEnglishUrl(isset($localeUrls['en']) ? $localeUrls['en'] : '');
        $this->setGermanUrl(isset($localeUrls['de']) ? $localeUrls['de'] : '');

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleUrl($locale)
    {
        return $this->getLocaleField('url', $locale, ['nl', 'en', 'de']);
    }

    /**
     * {@InheritDoc}
     */
    public function setExternalUrl($externalUrl)
    {
        $this->externalUrl = $externalUrl;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getExternalUrl()
    {
        return $this->externalUrl;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishExternalUrl($englishExternalUrl)
    {
        $this->englishExternalUrl = $englishExternalUrl;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishExternalUrl()
    {
        return $this->englishExternalUrl;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanExternalUrl($germanExternalUrl)
    {
        $this->germanExternalUrl = $germanExternalUrl;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanExternalUrl()
    {
        return $this->germanExternalUrl;
    }

    /**
     * {@InheritDoc}
     */
    public function setLocaleExternalUrls($localeExternalUrls)
    {
        // normalize locales
        $localeExternalUrls = array_change_key_case($localeExternalUrls);

        $this->setExternalUrl(isset($localeExternalUrls['nl']) ? $localeExternalUrls['nl'] : '');
        $this->setEnglishExternalUrl(isset($localeExternalUrls['en']) ? $localeExternalUrls['en'] : '');
        $this->setGermanExternalUrl(isset($localeExternalUrls['de']) ? $localeExternalUrls['de'] : '');

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleExternalUrl($locale)
    {
        return $this->getLocaleField('externalUrl', $locale, ['nl', 'en', 'de']);
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