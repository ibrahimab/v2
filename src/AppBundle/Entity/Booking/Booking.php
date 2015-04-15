<?php
namespace AppBundle\Entity\Booking;

use       AppBundle\Service\Api\Booking\BookingServiceEntityInterface;
use       AppBundle\Service\Api\Survey\SurveyServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;

/**
 * Booking
 *
 * @ORM\Table(name="boeking")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Booking\BookingRepository")
 */
class Booking implements BookingServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="boeking_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var SurveyServiceEntityInterface[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Booking\Survey\Survey", mappedBy="booking")
     */
    private $surveys;

    /**
     * @var string
     *
     * @ORM\Column(name="boekingsnummer", type="string", length=30)
     */
    private $bookingNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="boekingsnummer_oud", type="string", length=30)
     */
    private $oldBookingNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="boekingsnummer_nieuw", type="string", length=9)
     */
    private $newBookingNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="goedgekeurd", type="boolean")
     */
    private $approved;

    /**
     * @var boolean
     *
     * @ORM\Column(name="geannuleerd", type="boolean")
     */
    private $cancelled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="geannuleerd_op", type="datetime")
     */
    private $cancelledAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="geblokkeerd", type="boolean")
     */
    private $blocked;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vervallen_aanvraag", type="boolean")
     */
    private $expired;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer")
     */
    private $typeId;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Type\Type", inversedBy="bookings")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="type_id")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="naam_accommodatie", type="string", length=255)
     */
    private $accommodationName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="aankomstdatum", type="timestamp")
     */
    private $arrivalAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="aankomstdatum_exact", type="timestamp")
     */
    private $exactArrivalAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vertrekdatum_exact", type="timestamp")
     */
    private $exactDepartureAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="seizoen_id", type="integer")
     */
    private $seasonId;

    /**
     * @var integer
     *
     * @ORM\Column(name="aantalpersonen", type="integer")
     */
    private $persons;

    /**
     * @var integer
     *
     * @ORM\Column(name="debiteurnummer", type="integer")
     */
    private $debitNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="landcode", type="integer")
     */
    private $countryId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gezien", type="boolean")
     */
    private $seen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gewijzigd", type="datetime")
     */
    private $editedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=1)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="taal", type="string", length=2)
     */
    private $language;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->surveys = new ArrayCollection();
    }

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
    public function setBookingNumber($bookingNumber)
    {
        $this->bookingNumber = $bookingNumber;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getBookingNumber()
    {
        return $this->bookingNumber;
    }

    /**
     * {@InheritDoc}
     */
    public function setOldBookingNumber($oldBookingNumber)
    {
        $this->oldBookingNumber = $oldBookingNumber;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getOldBookingNumber()
    {
        return $this->oldBookingNumber;
    }

    /**
     * {@InheritDoc}
     */
    public function setNewBookingNumber($newBookingNumber)
    {
        $this->newBookingNumber = $newBookingNumber;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getNewBookingNumber()
    {
        return $this->newBookingNumber;
    }

    /**
     * {@InheritDoc}
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * {@InheritDoc}
     */
    public function setCancelled($cancelled)
    {
        $this->cancelled = $cancelled;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }

    /**
     * {@InheritDoc}
     */
    public function setCancelledAt($cancelledAt)
    {
        $this->cancelledAt = $cancelledAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getCancelledAt()
    {
        return $this->cancelledAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * {@InheritDoc}
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * {@InheritDoc}
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * {@InheritDoc}
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
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
    public function setAccommodationName($accommodationName)
    {
        $this->accommodationName = $accommodationName;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getAccommodationName()
    {
        return $this->accommodationName;
    }

    /**
     * {@InheritDoc}
     */
    public function setArrivalAt($arrivalAt)
    {
        $this->arrivalAt = $arrivalAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getArrivalAt()
    {
        return $this->arrivalAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setExactArrivalAt($exactArrivalAt)
    {
        $this->exactArrivalAt = $exactArrivalAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getExactArrivalAt()
    {
        return $this->exactArrivalAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setExactDepartureAt($exactDepartureAt)
    {
        $this->exactDepartureAt = $exactDepartureAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getExactDepartureAt()
    {
        return $this->exactDepartureAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setSeasonId($seasonId)
    {
        $this->seasonId = $seasonId;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSeasonId()
    {
        return $this->seasonId;
    }

    /**
     * {@InheritDoc}
     */
    public function setPersons($persons)
    {
        $this->persons = $persons;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * {@InheritDoc}
     */
    public function setDebitNumber($debitNumber)
    {
        $this->debitNumber = $debitNumber;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getDebitNumber()
    {
        return $this->debitNumber;
    }

    /**
     * {@InheritDoc}
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * {@InheritDoc}
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * {@InheritDoc}
     */
    public function setEditedAt($editedAt)
    {
        $this->editedAt = $editedAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEditedAt()
    {
        return $this->editedAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * {@InheritDoc}
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
