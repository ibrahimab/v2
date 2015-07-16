<?php
namespace AppBundle\Entity\Price;
use       AppBundle\Service\Api\Price\PriceServiceEntityInterface;
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
 * @ORM\Table(name="tarief")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Price\PriceRepository")
 */
class Price
{
    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="kortingactief", type="boolean")
     */
    private $discountActive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aanbiedingskleur_korting", type="boolean")
     */
    private $offerDiscountColor;

    /**
     * @var integer
     *
     * @ORM\Column(name="week", type="timestamp")
     */
    private $weekend;

    /**
     * @var integer
     *
     * @ORM\Column(name="bruto", type="integer")
     */
    private $bruto;

    /**
     * @var integer
     *
     * @ORM\Column(name="c_bruto", type="integer")
     */
    private $cbruto;

    /**
     * @var float
     *
     * @ORM\Column(name="arrangementsprijs", type="float")
     */
    private $arrangementPrice;

    /**
     * @var boolean
     *
     * @ORM\Column(name="beschikbaar", type="boolean")
     */
    private $available;
}