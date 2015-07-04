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
 * Option section
 *
 * @ORM\Table(name="optie_onderdeel")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Option\OptionRepository")
 */
class Section
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="optie_onderdeel_id", type="integer")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actief", type="boolean")
     */
    private $active;

    /**
     * @var int
     *
     * @ORM\Column(name="volgorde", type="smallint")
     */
    private $order;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tonen_accpagina", type="boolean")
     */
    private $showOnAccommodationPage;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Option\Group", inversedBy="sections")
     * @ORM\JoinColumn(name="optie_groep_id", referencedColumnName="optie_groep_id")
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="naam_en", type="string", length=100)
     */
    private $englishName;

    /**
     * @var string
     *
     * @ORM\Column(name="naam_de", type="string", length=100)
     */
    private $germanName;
}