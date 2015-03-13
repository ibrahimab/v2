<?php
namespace AppBundle\Service\Api\Country;

interface CountryServiceEntityInterface
{
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId();

    /**
     * Set name
     *
     * @param string $name
     * @return CountryServiceEntityInterface
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
     * @return CountryServiceEntityInterface
     */
    public function setAlternativeName($alternativeName);

    /**
     * Get alternativeName
     *
     * @return string 
     */
    public function getAlternativeName();

    /**
     * Set display
     *
     * @param boolean $display
     * @return CountryServiceEntityInterface
     */
    public function setDisplay($display);

    /**
     * Get display
     *
     * @return boolean 
     */
    public function getDisplay();

    /**
     * Set title
     *
     * @param string $title
     * @return CountryServiceEntityInterface
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle();

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return CountryServiceEntityInterface
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
     * @return CountryServiceEntityInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription();

    /**
     * Set colourCode
     *
     * @param integer $colourCode
     * @return CountryServiceEntityInterface
     */
    public function setColourCode($colourCode);

    /**
     * Get colourCode
     *
     * @return integer 
     */
    public function getColourCode();

    /**
     * Set accommodationCodes
     *
     * @param array $accommodationCodes
     * @return CountryServiceEntityInterface
     */
    public function setAccommodationCodes($accommodationCodes);

    /**
     * Get accommodationCodes
     *
     * @return array 
     */
    public function getAccommodationCodes();
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return CountryServiceEntityInterface
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
     * @return CountryServiceEntityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt();
}