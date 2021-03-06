<?php
namespace AppBundle\Entity\GeneralSettings;
use       AppBundle\Service\Api\GeneralSettings\GeneralSettingsServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;

/**
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
/**
 * GeneralSettings
 *
 * @ORM\Table("diverse_instellingen")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GeneralSettings\GeneralSettingsRepository")
 */
class GeneralSettings implements GeneralSettingsServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="diverse_instellingen_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="winternieuwsbrieven", type="text")
     */
    private $winterNewsletters;

    /**
     * @var string
     *
     * @ORM\Column(name="winternieuwsbrieven_be", type="text")
     */
    private $winterNewslettersBelgium;

    /**
     * @var string
     *
     * @ORM\Column(name="zomernieuwsbrieven", type="text")
     */
    private $summerNewsletters;

    /**
     * @var string
     *
     * @ORM\Column(name="zomernieuwsbrieven_be", type="text")
     */
    private $summerNewslettersBelgium;

    /**
     * @var string
     *
     * @ORM\Column(name="zoekformulier_weinig_tarieven_1", type="integer")
     */
    private $searchFormMessageSearchWithoutDatesWinter;

    /**
     * @var string
     *
     * @ORM\Column(name="zoekformulier_weinig_tarieven_2", type="integer")
     */
    private $searchFormMessageSearchWithoutDatesSummer;

    /**
     * @var string
     *
     * @ORM\Column(name="monitor_mysql", type="string", length=52)
     */
    private $monitorMySQL;

    /**
     * @var string
     *
     * @ORM\Column(name="winter_no_price_show_unavailable", type="integer")
     */
    private $winterNoPriceShowUnavailable;

    /**
     * @var string
     *
     * @ORM\Column(name="summer_no_price_show_unavailable", type="integer")
     */
    private $summerNoPriceShowUnavailable;

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
    public function setWinterNewsletters($winterNewsletters)
    {
        $this->winterNewsletters = $winterNewsletters;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWinterNewsletters()
    {
        return $this->winterNewsletters;
    }

    /**
     * {@InheritDoc}
     */
    public function setWinterNewslettersBelgium($winterNewslettersBelgium)
    {
        $this->winterNewslettersBelgium = $winterNewslettersBelgium;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWinterNewslettersBelgium()
    {
        return $this->winterNewslettersBelgium;
    }

    /**
     * {@InheritDoc}
     */
    public function setSummerNewsletters($summerNewsletters)
    {
        $this->summerNewsletters = $summerNewsletters;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSummerNewsletters()
    {
        return $this->summerNewsletters;
    }

    /**
     * {@InheritDoc}
     */
    public function setSummerNewslettersBelgium($summerNewslettersBelgium)
    {
        $this->summerNewslettersBelgium = $summerNewslettersBelgium;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSummerNewslettersBelgium()
    {
        return $this->summerNewslettersBelgium;
    }

    /**
     * {@InheritDoc}
     */
    public function setSearchFormMessageSearchWithoutDatesWinter($searchFormMessageSearchWithoutDatesWinter)
    {
        $this->searchFormMessageSearchWithoutDatesWinter = $searchFormMessageSearchWithoutDatesWinter;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSearchFormMessageSearchWithoutDatesWinter()
    {
        return $this->searchFormMessageSearchWithoutDatesWinter;
    }

    /**
     * {@InheritDoc}
     */
    public function setSearchFormMessageSearchWithoutDatesSummer($searchFormMessageSearchWithoutDatesSummer)
    {
        $this->searchFormMessageSearchWithoutDatesSummer = $searchFormMessageSearchWithoutDatesSummer;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSearchFormMessageSearchWithoutDatesSummer()
    {
        return $this->searchFormMessageSearchWithoutDatesSummer;
    }

    /**
     * {@InheritDoc}
     */
    public function setMonitorMySQL($monitorMySQL)
    {
        $this->monitorMySQL = $monitorMySQL;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getMonitorMySQL()
    {
        return $this->monitorMySQL;
    }

    /**
     * {@InheritDoc}
     */
    public function setWinterNoPriceShowUnavailable($winterNoPriceShowUnavailable)
    {
        $this->winterNoPriceShowUnavailable = $winterNoPriceShowUnavailable;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWinterNoPriceShowUnavailable()
    {
        return $this->winterNoPriceShowUnavailable;
    }

    /**
     * {@InheritDoc}
     */
    public function setSummerNoPriceShowUnavailable($summerNoPriceShowUnavailable)
    {
        $this->summerNoPriceShowUnavailable = $summerNoPriceShowUnavailable;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSummerNoPriceShowUnavailable()
    {
        return $this->summerNoPriceShowUnavailable;
    }

}
