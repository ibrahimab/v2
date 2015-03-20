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
