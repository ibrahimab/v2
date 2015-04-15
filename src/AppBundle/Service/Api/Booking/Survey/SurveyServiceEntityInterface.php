<?php
namespace AppBundle\Service\Api\Booking\Survey;

use       AppBundle\Service\Api\Booking\BookingServiceEntityInterface;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 * SurveyServiceEntityInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface SurveyServiceEntityInterface
{    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId(); 

    /**
     * Set booking
     *
     * @param BookingServiceEntityInterface $booking
     * @return SurveyServiceEntityInterface
     */
    public function setBooking($booking);

    /**
     * Get booking
     *
     * @return BookingServiceEntityInterface 
     */
    public function getBooking();

    /**
     * Set websiteText
     *
     * @param string $websiteText
     * @return SurveyServiceEntityInterface
     */
    public function setWebsiteText($websiteText);

    /**
     * Get websiteText
     *
     * @return string 
     */
    public function getWebsiteText();

    /**
     * Set websiteTextModified
     *
     * @param string $websiteTextModified
     * @return SurveyServiceEntityInterface
     */
    public function setWebsiteTextModified($websiteTextModified);

    /**
     * Get websiteText
     *
     * @return string 
     */
    public function getWebsiteTextModified();
    
    /**
     * Set website modified text in a certain language
     *
     * @param string $text
     * @param string $language
     * @return SurveyServiceEntityInterface
     */
    public function setWebsiteTextModifiedLanguage($text, $language);

    /**
     * Set language
     *
     * @param string $language
     * @return SurveyServiceEntityInterface
     */
    public function setLanguage($language);

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage();

    /**
     * Set websiteName
     *
     * @param string $websiteName
     * @return SurveyServiceEntityInterface
     */
    public function setWebsiteName($websiteName);

    /**
     * Get websiteName
     *
     * @return string 
     */
    public function getWebsiteName();

    /**
     * Set reviewed
     *
     * @param integer $reviewed
     * @return SurveyServiceEntityInterface
     */
    public function setReviewed($reviewed);

    /**
     * Get reviewed
     *
     * @return integer 
     */
    public function getReviewed();

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return SurveyServiceEntityInterface
     */
    public function setTypeId($typeId);

    /**
     * Get typeId
     *
     * @return integer 
     */
    public function getTypeId();

    /**
     * Set type
     *
     * @param TypeServiceEntityInterface $type
     * @return SurveyServiceEntityInterface
     */
    public function setType($type);

    /**
     * Get type
     *
     * @return TypeServiceEntityInterface
     */
    public function getType();

    /**
     * Set arrivalAt
     *
     * @param \DateTime $arrivalAt
     * @return SurveyServiceEntityInterface
     */
    public function setArrivalAt($arrivalAt);

    /**
     * Get arrivalAt
     *
     * @return \DateTime 
     */
    public function getArrivalAt();

    /**
     * Set departureAt
     *
     * @param \DateTime $departureAt
     * @return SurveyServiceEntityInterface
     */
    public function setDepartureAt($departureAt);

    /**
     * Get departureAt
     *
     * @return \DateTime 
     */
    public function getDepartureAt();

    /**
     * Set filledInAt
     *
     * @param \DateTime $filledInAt
     * @return SurveyServiceEntityInterface
     */
    public function setFilledInAt($filledInAt);

    /**
     * Get filledInAt
     *
     * @return \DateTime 
     */
    public function getFilledInAt();

    /**
     * Get overallRating
     *
     * @return integer 
     */
    public function getOverallRating();
    
    /**
     * @param array $answers
     * @return SurveyServiceEntityInterface
     */
    public function setAnswers($answers);
}