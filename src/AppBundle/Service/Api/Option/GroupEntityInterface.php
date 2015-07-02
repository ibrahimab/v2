<?php
namespace AppBundle\Service\Api\Option;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
interface GroupEntityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Setting kind
     *
     * @param KindEntityInterface $kind
     */
    public function setKind($kind);

    /**
     * Getting kind
     *
     * @return KindEntityInterface
     */
    public function getKind();

    /**
     * Set name
     *
     * @param string $name
     * @return GroupEntityInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Setting duration
     *
     * @param integer $duration
     * @return GroupEntityInterface
     */
    public function setDuration($duration);

    /**
     * @return integer
     */
    public function getDuration();

    /**
     * Set description
     *
     * @param string $description
     * @return GroupEntityInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return GroupEntityInterface
     */
    public function setEnglishDescription($englishDescription);

    /**
     * Get description
     *
     * @return string
     */
    public function getEnglishDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return GroupEntityInterface
     */
    public function setGermanDescription($germanDescription);

    /**
     * Get description
     *
     * @return string
     */
    public function getGermanDescription();

    /**
     * Set locale descriptions
     *
     * @param array $localeDescriptions
     * @return GroupEntityInterface
     */
    public function setLocaleDescriptions($localeDescriptions);

    /**
     * Get locale description
     *
     * @return string
     */
    public function getLocaleDescription($locale);
}