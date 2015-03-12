<?php
namespace AppBundle\Entity\Accommodation;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
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


    public function __construct()
    {
        $this->types = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param  TypeServiceEntityInterface[] $types
     * @return Accommodation
     */
    public function setTypes($types)
    {
        $this->types = $types;
        
        return $this;
    }
    
    /**
     * @return TypeServiceEntityInterface[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Set naam
     *
     * @param string $naam
     * @return Accommodation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get naam
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
