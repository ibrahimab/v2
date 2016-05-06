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
     * @var string
     *
     * @ORM\Column(name="naam", type="string")
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="zoekvolgorde", type="smallint")
     */
    private $searchOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="opmerkingen_intern", type="text", nullable=true)
     */
    private $internalComments;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $searchOrder
     * @return Supplier
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    /**
     * Set url
     *
     * @param string $url
     * @return Supplier
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set internalComments
     *
     * @param string $internalComments
     * @return Supplier
     */
    public function setInternalComments($internalComments)
    {
        $this->internalComments = $internalComments;

        return $this;
    }

    /**
     * Get internalComments
     *
     * @return string 
     */
    public function getInternalComments()
    {
        return $this->internalComments;
    }
}
