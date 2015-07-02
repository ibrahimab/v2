<?php
namespace AppBundle\Entity\Season;
use       AppBundle\Service\Api\Season\SeasonServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;

/**
 * Season Entity
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
/**
 * Season
 *
 * @ORM\Table(name="seizoen")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Season\SeasonRepository")
 */
class Season implements SeasonServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="seizoen_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=50)
     */
    private $name;

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
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $season;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begin", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eind", type="datetime")
     */
    private $end;

    /**
     * @var float
     *
     * @ORM\Column(name="verzekeringen_poliskosten", type="float")
     */
    private $insurancesPolicyCosts;

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
        return $this->getLocaleField('name', $locale, ['nl', 'en', 'de']);
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
    public function getInsurancesPolicyCosts()
    {
        return $this->insurancesPolicyCosts;
    }

    /**
     * {@InheritDoc}
     */
    public function setInsurancesPolicyCosts($insurancesPolicyCosts)
    {
        $this->insurancesPolicyCosts = $insurancesPolicyCosts;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnd()
    {
        return $this->end;
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