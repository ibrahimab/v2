<?php
namespace AppBundle\Document\Autocomplete;
use       AppBundle\Service\Api\Autocomplete\AutocompleteServiceDocumentInterface;
use 	  Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Autocomplete Document
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
/** 
 * @ODM\Document(collection="autocomplete", repositoryClass="AppBundle\Document\Autocomplete\AutocompleteRepository")
 */
class Autocomplete implements AutocompleteServiceDocumentInterface
{
    /**
     * @ODM\Id
     */
    private $_id;
    
    /**
     * @ODM\String
     */
    private $name;
    
    /**
     * @ODM\String
     */
    private $type;
    
    /**
     * @ODM\Int
     */
    private $type_id;
    
    /**
     * @ODM\Collection
     */
    private $websites;
    
    /**
     * @ODM\Int
     */
    private $order;
    
    /**
     * @ODM\Collection
     */
    private $locales;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->websites = [];
        $this->locales  = [];
    }
    
    /**
     * {@InheritDoc}
     */
    public function get_Id()
    {
        return $this->_id;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setType($type)
    {
        $this->type = $type;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getTypeId()
    {
        return $this->type_id;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setWebsites($websites)
    {
        $this->websites = $websites;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getWebsites()
    {
        return $this->websites;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setOrder($order)
    {
        $this->order = $order;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setLocales($locales)
    {
        $this->locales = $locales;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getLocales()
    {
        return $this->locales;
    }
}