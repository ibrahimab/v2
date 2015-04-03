<?php
namespace AppBundle\Entity\Highlight;

use       AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;
use       JsonSerializable;

/**
 * Highlight
 *
 * @ORM\Table(name="hoogtepunt")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Highlight\HighlightRepository")
 */
class Highlight implements HighlightServiceEntityInterface, JsonSerializable
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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Type\Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="type_id")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer")
     */
    private $type_id;
    
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
     * {@InheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@InheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setType($type)
    {
        $this->type = $type;
        
        return $this->type;
    }

    /**
     * {@InheritDoc}
     */
    public function setTypeId($typeId)
    {
        $this->type_id = $typeId;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getTypeId()
    {
        return $this->type_id;
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
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * {@InheritDoc}
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
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
    
    public function jsonSerialize()
    {
        return [
            'test' => 'me',
        ];
    }
}
