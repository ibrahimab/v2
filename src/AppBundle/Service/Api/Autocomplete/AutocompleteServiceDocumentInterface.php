<?php
namespace AppBundle\Service\Api\Autocomplete;

/**
 * AutocompleteService Document
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
interface AutocompleteServiceDocumentInterface
{
    /**
     * Get ID
     *
     * @return \MongoId
     */
    public function get_Id();
    
    /**
     * Get name
     *
     * @param  string $name
     * @return AutocompleteServiceDocumentInterface
     */
    public function setName($name);
    
    /**
     * Get name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Set type
     *
     * @param  string $type
     * @return AutocompleteServiceDocumentInterface
     */
    public function setType($type);
    
    /**
     * Get type
     *
     * @return string
     */
    public function getType();
    
    /**
     * Set Type ID
     *
     * @param  int $type_id
     * @return AutocompleteServiceDocumentInterface
     */
    public function setTypeId($type_id);
    
    /**
     * Get Type ID
     *
     * @return int
     */
    public function getTypeId();
    
    /**
     * Set websites
     *
     * @param  array $websites
     * @return AutocompleteServiceDocumentInterface
     */
    public function setWebsites($websites);
    
    /**
     * Get websites
     *
     * @return array
     */
    public function getWebsites();
    
    /**
     * Set order
     *
     * @param  int $order
     * @return AutocompleteServiceDocumentInterface
     */
    public function setOrder($order);
    
    /**
     * Get order
     *
     * @return int
     */
    public function getOrder();
    
    /**
     * Get locales
     *
     * @param  array $locales
     * @return AutocompleteServiceDocumentInterface
     */
    public function setLocales($locales);
    
    /**
     * Get locales
     *
     * @return array
     */
    public function getLocales();
}