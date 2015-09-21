<?php
namespace AppBundle\Entity\Price;
use       Doctrine\ORM\Mapping AS ORM;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
/**
 * Price
 *
 * @ORM\Table(name="tarief_personen")
 * @ORM\Entity
 */
class Person
{
    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="seizoen_id", type="integer")
     */
    private $seasonId;

    /**
     * @var integer
     *
     * @ORM\Column(name="week", type="integer")
     */
    private $weekend;

    /**
     * @var integer
     *
     * @ORM\Column(name="personen", type="integer")
     */
    private $persons;

    /**
     * @var float
     *
     * @ORM\Column(name="prijs", type="float")
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="afwijking", type="float")
     */
    private $deviation;
}