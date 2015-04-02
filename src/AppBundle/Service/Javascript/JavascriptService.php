<?php
namespace AppBundle\Service\Javascript;

use       AppBundle\Service\Javascript\Exception\ExistsException;
use       AppBundle\Service\Javascript\Exception\NotFoundException;

/**
 * JavascriptService exposes a simple API to manage the global JS object that is available
 * to javascript files. This allows you to mange this object via PHP across the application 
 * where the service container is available.
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @since   0.0.1
 */
class JavascriptService
{
    /**
     * Main internal storage
     *
     * @var ArrayObject
     */
    protected $attributes;
    
    /**
     * Constructor
     *
     * @param array $initial
     */
    public function __construct(Array $initial = [])
    {
        $this->attributes = $initial;
    }
    
    /**
     * Checking if attribute exists
     */
    public function exists($attribute)
    {   
        try {
            
            $this->get($attribute);
            return true;
            
        } catch (NotFoundException $e) {
            return false;
        }
    }
    
    /**
     * Set attribute, but only if it does not exist.
     *
     * @param string $attribute
     * @param mixed  $value
     * @return JavascriptService
     * @throws ExistsException
     */
    public function set($attribute, $value = null)
    {
        $this->dot($attribute, function(&$foundAttribute) use ($value) {
            $foundAttribute = $value;
        });
        
        return $this;
    }
    
    /**
     * Unset attribute, if it exists
     *
     * @param string $attribute
     * @return JavascriptService
     */
    public function delete($attribute)
    {
        if (true === $this->exists($attribute)) {
            
            $this->dot($attribute, function(&$foundAttribute) {
                $foundAttribute = null;
            });
        }
        
        return $this;
    }
    
    /**
     * Get requested attribute or throw an exception if not found
     *
     * @param string $attribute
     * @return mixed
     * @throws NotFoundException
     */
    public function get($attribute)
    {   
        $result = $this->dot($attribute);
        if (null === $result) {
            throw new NotFoundException(vsprintf('%s could not be found', [$attribute]));
        }
        
        return $result;
    }
    
    /**
     * Add element to attribute which is an array itself
     *
     * @param string $attribute
     * @param mixed $value
     * @return JavascriptService
     * @throws NotFoundException
     * @throws NoArrayException
     */
    public function push($attribute, $value)
    {
        if (false === $this->exists($attribute)) {
            throw new NotFoundException(vsprintf('%s could not be found', [$attribute]));
        }
        
        $this->dot($attribute, function(&$foundAttribute) use ($value) {
            
            if (is_array($foundAttribute)) {
                array_push($foundAttribute, $value);
            } else {
                throw new NoArrayException(vsprintf('%s is not an array, cannot add element', $attribute));
            }
        });
        
        return $this;
    }
    
    /**
     * Pop element from attribute that is an array itself
     *
     * @param string $attribute
     * @return mixed
     * @throws JavascriptService
     */
    public function pop($attribute)
    {
        if (false === $this->exists($attribute)) {
            throw new NotFoundException(vsprintf('%s could not be found', [$attribute]));
        }
        
        return $this->dot($attribute, function(&$foundAttribute) {
            
            if (is_array($foundAttribute)) {
                return array_pop($foundAttribute);
            } else {
                throw new NoArrayException(vsprintf('%s is not an array, cannot pop element', $attribute));
            }
        });
    }
    
    /**
     * Add element to attribute which is a dictionary
     *
     * @param string $attribute
     * @param string $key
     * @param mixed $value
     * @param boolean $initialize
     * @return JavascriptService
     * @throws NotFoundException
     */
    public function add($attribute, $key, $value, $initialize = false)
    {
        if (false === $this->exists($attribute) && false === $initialize) {
            throw new NotFoundException(vsprintf('%s could not be found', [$attribute]));
        }
        
        if (false === $this->exists($attribute) && true === $initialize) {
            $this->attributes[$attribute] = [];
        }
        
        $this->attributes[$attribute][$key] = $value;
        
        return $this;
    }
    
    /**
     * Remove subelement from dictionary of an attribute
     * 
     * @param string $attribute
     * @param string $key
     * @return mixed
     * @throws NotFoundException
     */
    public function remove($attribute, $key)
    {
        if (false === $this->exists($attribute)) {
            throw new NotFoundException(vsprintf('%s could not be found', [$attribute]));
        }
        
        if (false === isset($this->attributes[$attribute][$key])) {
            throw new NotFoundException(vsprintf('%s could not be found in dictionary of %s', [$key, $attribute]));
        }
        
        $value = $this->attributes[$attribute][$key];
        unset($this->attributes[$attribute][$key]);
        
        return $value;
    }
    
    /**
     * Return a copy of the internal storage
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }
    
    public function dot($attribute, $callback = null)
    {
        $current =& $this->attributes;
        $path    =  strtok($attribute, '.');
        
        while (false !== $path) {
            
            $current =& $current[$path];
            $path    =  strtok('.');
        }
        
        return (null !== $callback ? $callback($current) : $current);
    }
}