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

    /**
     * @var integer
     *
     * @ORM\Column(name="zoekvolgorde", type="smallint")
     */
    private $searchOrder;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $searchOrder
     * @return Supplier
     */
    public function setSearchOrder($searchOrder)
    {
        $this->searchOrder = $searchOrder;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSearchOrder()
    {
        return $this->searchOrder;
    }

    /**
     * @param array $data
     * @return Supplier
     */
    public static function hydrate($data)
    {
        $supplier = new self();

        foreach ($data as $field => $value) {

            if (property_exists($supplier, $field)) {
                $supplier->{$field} = $value;
            }
        }

        return $supplier;
    }
}