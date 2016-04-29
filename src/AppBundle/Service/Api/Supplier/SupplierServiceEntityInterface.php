<?php
namespace AppBundle\Service\Api\Supplier;

/**
 * SupplierServiceEntityInterface
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 */

interface SupplierServiceEntityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set name
     *
     * @param string $name
     * @return SupplierServiceEntityInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SupplierServiceEntityInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return SupplierServiceEntityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt();
}
