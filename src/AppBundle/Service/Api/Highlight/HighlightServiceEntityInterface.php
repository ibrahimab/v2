<?php
namespace AppBundle\Service\Api\Highlight;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 * The Highlight Service returns either a single entity or an array of entities
 * These entities must implement this interface
 */
interface HighlightServiceEntityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set Type
     * 
     * @param  TypeServiceEntityInterface $type
     * @return HighlightServiceEntityInterface
     */
    public function setType($type);
    
    /**
     * @return TypeServiceEntityInterface|null
     */
    public function getType();

    /**
     * Set Type ID
     *
     * @param integer $typeId
     * @return HighlightServiceEntityInterface
     */
    public function setTypeId($typeId);

    /**
     * Get type ID
     *
     * @return integer
     */
    public function getTypeId();

    /**
     * Set display
     *
     * @param boolean $display
     * @return HighlightServiceEntityInterface
     */
    public function setDisplay($display);

    /**
     * Get display
     *
     * @return boolean
     */
    public function getDisplay();

    /**
     * Set season
     *
     * @param integer $season
     * @return HighlightServiceEntityInterface
     */
    public function setSeason($season);

    /**
     * Get season
     *
     * @return integer
     */
    public function getSeason();

    /**
     * Set websites
     *
     * @param array $websites
     * @return HighlightServiceEntityInterface
     */
    public function setWebsites($websites);

    /**
     * Get websites
     *
     * @return array
     */
    public function getWebsites();

    /**
     * Set rank
     *
     * @param integer $rank
     * @return HighlightServiceEntityInterface
     */
    public function setRank($rank);

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank();

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return HighlightServiceEntityInterface
     */
    public function setPublishedAt($publishedAt);

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt();

    /**
     * Set expiredAt
     *
     * @param \DateTime $expiredAt
     * @return HighlightServiceEntityInterface
     */
    public function setExpiredAt($expiredAt);

    /**
     * Get expiredAt
     *
     * @return \DateTime
     */
    public function getExpiredAt();

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return HighlightServiceEntityInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return HighlightServiceEntityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt();
}