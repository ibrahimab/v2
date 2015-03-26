<?php
namespace AppBundle\Entity\Accommodation;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;
use       Doctrine\Common\Collections\ArrayCollection;

/**
 * Accommodation Entity
 *
 * @ORM\Table(name="accommodatie")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Accommodation\AccommodationRepository")
 */
class Accommodation implements AccommodationServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="accommodatie_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var TypeServiceEntityInterface[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Type\Type", mappedBy="accommodation")
     */
    private $types;

    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=255)
     */
    private $name;
    
    /**
     * @var PlaceServiceEntityInterface
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Place\Place", inversedBy="accommodations")
     * @ORM\JoinColumn(name="plaats_id", referencedColumnName="plaats_id")
     */
    private $place;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="soortaccommodatie", type="integer")
     */
    private $kind;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="tonen", type="boolean")
     */
    private $display;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="weekendski", type="boolean")
     */
    private $weekendSki;
    
    /**
     * Kind identifiers for translations
     *
     * @var array
     */
    private static $kindIdentifiers = [

        self::KIND_CHALET           => 'chalet',
        self::KIND_APARTMENT        => 'apartment',
        self::KIND_HOTEL            => 'hotel',
        self::KIND_CHALET_APARTMENT => 'chalet-apartment',
        self::KIND_HOLIDAY_HOUSE    => 'holiday-house',
        self::KIND_VILLA            => 'villa',
        self::KIND_CASTLE           => 'castle',
        self::KIND_HOLIDAY_PARK     => 'holiday-park',
        self::KIND_AGRITURISMO      => 'agriturismo',
        self::KIND_DOMAIN           => 'domain',
        self::KIND_PENSION          => 'pension',
    ];


    public function __construct()
    {
        $this->types = new ArrayCollection();
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
    public function setTypes($types)
    {
        $this->types = $types;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * {@InheritDoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@InheritDoc}
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getPlace()
    {
        return $this->place;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getKind()
    {
        return $this->kind;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getKindIdentifier()
    {
        return (isset(self::$kindIdentifiers[$this->getKind()]) ? self::$kindIdentifiers[$this->getKind()] : null);
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
    public function setWeekendSki($weekendSki)
    {
        $this->weekendSki = $weekendSki;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWeekendSki()
    {
        return $this->weekendSki;
    }
}
