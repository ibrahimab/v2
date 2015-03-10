<?php
namespace AppBundle\Entity\Accommodation;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;

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
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=255)
     */
    private $name;


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
