<?php
namespace AppBundle\Annotation;

use       Doctrine\Common\Annotations\Annotation;
/**
 * BreadcrumbAnnotation
 * 
 * This class allows you to define breadcrumbs using Annotations
 * directly inside your controller
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @since   0.0.1
 *
 * @Annotation
 */
class Breadcrumb
{
    private $name;
    private $title;
    private $translate;
    private $path;
    private $pathParams;
    private $active;
    private $level;
    
    public function __construct(array $values)
    {
        $this->name       = (isset($values['name'])       ? $values['name']       : null);
        $this->title      = (isset($values['title'])      ? $values['title']      : null);
        $this->translate  = (isset($values['translate'])  ? $values['translate']  : false);
        $this->path       = (isset($values['path'])       ? $values['path']       : null);
        $this->pathParams = (isset($values['pathParams']) ? $values['pathParams'] : []);
        $this->active     = (isset($values['active'])     ? $values['active']     : false);
        $this->level      = (isset($values['level'])      ? $values['level']      : null);
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function translate()
    {
        return $this->translate;
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function getPathParams()
    {
        return $this->pathParams;
    }
    
    public function getActive()
    {
        return $this->active;
    }
    
    public function setLevel($level)
    {
        $this->level = $level;
        
        return $this;
    }
    
    public function getLevel()
    {
        return $this->level;
    }
}