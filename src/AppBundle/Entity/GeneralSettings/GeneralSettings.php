<?php
namespace AppBundle\Entity\GeneralSettings;

use       AppBundle\Service\Api\GeneralSettings\GeneralSettingsServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set winterNewsletters
     *
     * @param string $winterNewsletters
     * @return GeneralSettings
     */
    public function setWinterNewsletters($winterNewsletters)
    {
        $this->winterNewsletters = $winterNewsletters;

        return $this;
    }

    /**
     * Get winterNewsletters
     *
     * @return string
     */
    public function getWinterNewsletters()
    {
        return $this->winterNewsletters;
    }

    /**
     * Set winterNewslettersBelgium
     *
     * @param string $winterNewslettersBelgium
     * @return GeneralSettings
     */
    public function setWinterNewslettersBelgium($winterNewslettersBelgium)
    {
        $this->winterNewslettersBelgium = $winterNewslettersBelgium;

        return $this;
    }

    /**
     * Get winterNewslettersBelgium
     *
     * @return string
     */
    public function getWinterNewslettersBelgium()
    {
        return $this->winterNewslettersBelgium;
    }

    /**
     * Set summerNewsletters
     *
     * @param string $summerNewsletters
     * @return GeneralSettings
     */
    public function setSummerNewsletters($summerNewsletters)
    {
        $this->summerNewsletters = $summerNewsletters;

        return $this;
    }

    /**
     * Get summerNewsletters
     *
     * @return string
     */
    public function getSummerNewsletters()
    {
        return $this->summerNewsletters;
    }

    /**
     * Set summerNewslettersBelgium
     *
     * @param string $summerNewslettersBelgium
     * @return GeneralSettings
     */
    public function setSummerNewslettersBelgium($summerNewslettersBelgium)
    {
        $this->summerNewslettersBelgium = $summerNewslettersBelgium;

        return $this;
    }

    /**
     * Get summerNewslettersBelgium
     *
     * @return string
     */
    public function getSummerNewslettersBelgium()
    {
        return $this->summerNewslettersBelgium;
    }
}
