<?php
namespace AppBundle\Entity\Highlight;
use       AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;

/**
 * Highlight
 *
 * @ORM\Table(name="hoogtepunt")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Highlight\HighlightRepository")
 */
class Highlight implements HighlightServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="hoogtepunt_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer")
     */
    private $typeId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tonen", type="boolean")
     */
    private $display;

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
     * @var integer
     *
     * @ORM\Column(name="volgorde", type="integer")
     */
    private $rank;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begindatum", type="date")
     */
    private $publishedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="einddatum", type="date")
     */
    private $expiredAt;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return Highlight
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get typeId
     *
     * @return integer 
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set display
     *
     * @param boolean $display
     * @return Highlight
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * Get display
     *
     * @return boolean 
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set season
     *
     * @param integer $season
     * @return Highlight
     */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return integer 
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set websites
     *
     * @param array $websites
     * @return Highlight
     */
    public function setWebsites($websites)
    {
        $this->websites = $websites;

        return $this;
    }

    /**
     * Get websites
     *
     * @return array 
     */
    public function getWebsites()
    {
        return $this->websites;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return Highlight
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return Highlight
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime 
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set expiredAt
     *
     * @param \DateTime $expiredAt
     * @return Highlight
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * Get expiredAt
     *
     * @return \DateTime 
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Highlight
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Highlight
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
