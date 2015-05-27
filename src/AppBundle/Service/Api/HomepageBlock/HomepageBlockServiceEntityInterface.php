<?php
namespace AppBundle\Service\Api\HomepageBlock;

interface HomepageBlockServiceEntityInterface
{
    const POSITION_LEFT  = 1;
    const POSITION_RIGHT = 2;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId();

    /**
     * Set display
     *
     * @param integer $display
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setDisplay($display);

    /**
     * Get display
     *
     * @return integer 
     */
    public function getDisplay();

    /**
     * Set season
     *
     * @param integer $season
     * @return HomepageBlocksServiceEntityInterface
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
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setWebsites($websites);

    /**
     * Get websites
     *
     * @return array 
     */
    public function getWebsites();

    /**
     * Set link
     *
     * @param string $link
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setLink($link);

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink();

    /**
     * Set title
     *
     * @param string $title
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle();

    /**
     * Set englishTitle
     *
     * @param string $englishTitle
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setEnglishTitle($englishTitle);

    /**
     * Get englishTitle
     *
     * @return string 
     */
    public function getEnglishTitle();

    /**
     * Set germanTitle
     *
     * @param string $germanTitle
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setGermanTitle($germanTitle);

    /**
     * Get germanTitle
     *
     * @return string 
     */
    public function getGermanTitle();
    
    /**
     * Set locale titles
     * 
     * @param array $localeTitles
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setLocaleTitles($localeTitles);
    
    /**
     * Get locale title
     *
     * @param string $locale
     * @return string
     */
    public function getLocaleTitle($locale);

    /**
     * Set button
     *
     * @param string $button
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setButton($button);

    /**
     * Get button
     *
     * @return string 
     */
    public function getButton();

    /**
     * Set englishButton
     *
     * @param string $englishButton
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setEnglishButton($englishButton);

    /**
     * Get englishButton
     *
     * @return string 
     */
    public function getEnglishButton();

    /**
     * Set germanButton
     *
     * @param string $germanButton
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setGermanButton($germanButton);

    /**
     * Get germanButton
     *
     * @return string 
     */
    public function getGermanButton();
    
    /**
     * Set locale titles
     * 
     * @param array $localeButtons
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setLocaleButtons($localeButtons);
    
    /**
     * Get locale button
     *
     * @param string $locale
     * @return string
     */
    public function getLocaleButton($locale);

    /**
     * Set rank
     *
     * @param integer $rank
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setRank($rank);

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank();

    /**
     * Set position
     *
     * @param integer $position
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setPosition($position);

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition();

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return HomepageBlocksServiceEntityInterface
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
     * @return HomepageBlocksServiceEntityInterface
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
     * @return HomepageBlocksServiceEntityInterface
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
     * @return HomepageBlocksServiceEntityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt();
}