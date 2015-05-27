<?php
namespace AppBundle\Service\Api\Booking;

/**
 * BookingServiceEntityInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface BookingServiceEntityInterface
{
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId();

    /**
     * Set bookingNumber
     *
     * @param string $bookingNumber
     * @return BookingServiceEntityInterface
     */
    public function setBookingNumber($bookingNumber);

    /**
     * Get bookingNumber
     *
     * @return string 
     */
    public function getBookingNumber();

    /**
     * Set oldBookingNumber
     *
     * @param string $oldBookingNumber
     * @return BookingServiceEntityInterface
     */
    public function setOldBookingNumber($oldBookingNumber);

    /**
     * Get oldBookingNumber
     *
     * @return string 
     */
    public function getOldBookingNumber();

    /**
     * Set newBookingNumber
     *
     * @param string $newBookingNumber
     * @return BookingServiceEntityInterface
     */
    public function setNewBookingNumber($newBookingNumber);

    /**
     * Get newBookingNumber
     *
     * @return string 
     */
    public function getNewBookingNumber();

    /**
     * Set approved
     *
     * @param boolean $approved
     * @return BookingServiceEntityInterface
     */
    public function setApproved($approved);

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved();

    /**
     * Set cancelled
     *
     * @param boolean $cancelled
     * @return BookingServiceEntityInterface
     */
    public function setCancelled($cancelled);

    /**
     * Get cancelled
     *
     * @return boolean 
     */
    public function getCancelled();

    /**
     * Set cancelledAt
     *
     * @param \DateTime $cancelledAt
     * @return BookingServiceEntityInterface
     */
    public function setCancelledAt($cancelledAt);

    /**
     * Get cancelledAt
     *
     * @return \DateTime 
     */
    public function getCancelledAt();

    /**
     * Set blocked
     *
     * @param boolean $blocked
     * @return BookingServiceEntityInterface
     */
    public function setBlocked($blocked);

    /**
     * Get blocked
     *
     * @return boolean 
     */
    public function getBlocked();

    /**
     * Set expired
     *
     * @param boolean $expired
     * @return BookingServiceEntityInterface
     */
    public function setExpired($expired);

    /**
     * Get expired
     *
     * @return boolean 
     */
    public function getExpired();

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return BookingServiceEntityInterface
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
     * @param integer $type
     * @return BookingServiceEntityInterface
     */
    public function setType($type);

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType();

    /**
     * Set accommodationName
     *
     * @param string $accommodationName
     * @return BookingServiceEntityInterface
     */
    public function setAccommodationName($accommodationName);

    /**
     * Get accommodationName
     *
     * @return string 
     */
    public function getAccommodationName();

    /**
     * Set arrivalAt
     *
     * @param \DateTime $arrivalAt
     * @return BookingServiceEntityInterface
     */
    public function setArrivalAt($arrivalAt);

    /**
     * Get arrivalAt
     *
     * @return \DateTime 
     */
    public function getArrivalAt();

    /**
     * Set exactArrivalAt
     *
     * @param \DateTime $exactArrivalAt
     * @return BookingServiceEntityInterface
     */
    public function setExactArrivalAt($exactArrivalAt);

    /**
     * Get exactArrivalAt
     *
     * @return \DateTime 
     */
    public function getExactArrivalAt();

    /**
     * Set exactDepartureAt
     *
     * @param \DateTime $exactDepartureAt
     * @return BookingServiceEntityInterface
     */
    public function setExactDepartureAt($exactDepartureAt);

    /**
     * Get exactDepartureAt
     *
     * @return \DateTime 
     */
    public function getExactDepartureAt();

    /**
     * Set seasonId
     *
     * @param integer $seasonId
     * @return BookingServiceEntityInterface
     */
    public function setSeasonId($seasonId);

    /**
     * Get seasonId
     *
     * @return integer 
     */
    public function getSeasonId();

    /**
     * Set persons
     *
     * @param integer $persons
     * @return BookingServiceEntityInterface
     */
    public function setPersons($persons);

    /**
     * Get persons
     *
     * @return integer 
     */
    public function getPersons();

    /**
     * Set debitNumber
     *
     * @param integer $debitNumber
     * @return BookingServiceEntityInterface
     */
    public function setDebitNumber($debitNumber);

    /**
     * Get debitNumber
     *
     * @return integer 
     */
    public function getDebitNumber();

    /**
     * Set countryId
     *
     * @param integer $countryId
     * @return BookingServiceEntityInterface
     */
    public function setCountryId($countryId);

    /**
     * Get countryId
     *
     * @return integer 
     */
    public function getCountryId();

    /**
     * Set seen
     *
     * @param boolean $seen
     * @return BookingServiceEntityInterface
     */
    public function setSeen($seen);

    /**
     * Get seen
     *
     * @return boolean 
     */
    public function getSeen();

    /**
     * Set editedAt
     *
     * @param \DateTime $editedAt
     * @return BookingServiceEntityInterface
     */
    public function setEditedAt($editedAt);

    /**
     * Get editedAt
     *
     * @return \DateTime 
     */
    public function getEditedAt();

    /**
     * Set website
     *
     * @param string $website
     * @return BookingServiceEntityInterface
     */
    public function setWebsite($website);

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite();

    /**
     * Set language
     *
     * @param string $language
     * @return BookingServiceEntityInterface
     */
    public function setLanguage($language);

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage();
}