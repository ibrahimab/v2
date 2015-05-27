<?php
namespace AppBundle\Annotation\Parser;

use       Doctrine\Common\Annotations\Reader;

class Parser
{
    /**
     * @var Reader
     */
    private $reader;
    
    /**
     * @var string
     */
    protected $annotation;
    
    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
    
    /**
     * @param mixed $annotatedObject
     * @param string $annotatedMethod
     * @return array
     */
    public function parse($annotatedObject, $annotatedMethod)
    {
        $reflection            = new \ReflectionObject($annotatedObject);
        $method                = $reflection->getMethod($annotatedMethod);
        $classAnnotations      = $this->reader->getClassAnnotations($reflection);
        $methodAnnotations     = $this->reader->getMethodAnnotations($method);
        $unfilteredAnnotations = array_merge($classAnnotations, $methodAnnotations);
        $annotations           = [];
        
        foreach ($unfilteredAnnotations as $annotation) {
        
            if ($annotation instanceof $this->annotation) {
                $annotations[] = $annotation;
            }
        }
        
        return $annotations;
    }
}