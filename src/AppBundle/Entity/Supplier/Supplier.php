<?php
namespace AppBundle\Entity\Supplier;
use       Doctrine\ORM\Mapping as ORM;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
/**
 * Supplier
 *
 * @ORM\Table(name="leverancier")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Supplier\SupplierRepository")
 */
class Supplier
{
    /**
     * @var integer
     *
     * @ORM\Column(name="leverancier_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
}