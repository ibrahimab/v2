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
}
