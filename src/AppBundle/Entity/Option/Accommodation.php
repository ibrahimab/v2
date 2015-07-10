<?php
namespace AppBundle\Entity\Option;
use       Doctrine\ORM\Mapping AS ORM;
use       Doctrine\Common\Collections\ArrayCollection;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
/**
 * Option accommodation
 *
 * @ORM\Table(name="optie_accommodatie")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Option\OptionRepository")
 */
class Accommodation
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="accommodatie_id", type="integer")
     */
    private $id;
    
    /**
     * @var Kind
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Option\Kind", mappedBy="accommodation")
     */
    private $kinds;
    
    /**
     * @var int
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Option\Group")
     * @ORM\JoinColumn(name="optie_groep_id", referencedColumnName="optie_groep_id")
     */
    private $group;
    
    public function __construct()
    {
        $this->kinds = new ArrayCollection();
    }
}