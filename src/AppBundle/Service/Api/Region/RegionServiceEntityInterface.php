<?php
namespace AppBundle\Service\Api\Region;

interface RegionServiceEntityInterface
{
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId();

    /**
     * Set season
     *
     * @param integer $season
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
     */
    public function setWebsites($websites);

    /**
     * Get websites
     *
     * @return array 
     */
    public function getWebsites();

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return RegionServiceEntityInterface
     */
    public function setShortDescription($shortDescription);

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return RegionServiceEntityInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription();

    /**
     * Set minimumAltitude
     *
     * @param integer $minimumAltitude
     * @return RegionServiceEntityInterface
     */
    public function setMinimumAltitude($minimumAltitude);

    /**
     * Get minimumAltitude
     *
     * @return integer 
     */
    public function getMinimumAltitude();

    /**
     * Set maximumAltitude
     *
     * @param integer $maximumAltitude
     * @return RegionServiceEntityInterface
     */
    public function setMaximumAltitude($maximumAltitude);

    /**
     * Get maximumAltitude
     *
     * @return integer 
     */
    public function getMaximumAltitude();

    /**
     * Set name
     *
     * @param string $name
     * @return RegionServiceEntityInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string 
     */
    public function getName();

    /**
     * Set alternativeName
     *
     * @param string $alternativeName
     * @return RegionServiceEntityInterface
     */
    public function setAlternativeName($alternativeName);

    /**
     * Get alternativeName
     *
     * @return string 
     */
    public function getAlternativeName();

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt();
}