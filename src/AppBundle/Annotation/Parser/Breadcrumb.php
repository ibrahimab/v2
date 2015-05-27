<?php
namespace AppBundle\Annotation\Parser;

class Breadcrumb extends Parser
{
    protected $annotation = 'AppBundle\\Annotation\\Breadcrumb';
    
    public function parse($annotationObject, $annotationMethod)
    {
        $unsortedAnnotations = parent::parse($annotationObject, $annotationMethod);
        $sortedAnnotations   = [];
        $level               = 1;
        
        foreach ($unsortedAnnotations as $unsortedAnnotation) {
            
            if (null === $unsortedAnnotation->getLevel()) {
                $unsortedAnnotation->setLevel('z' . $level);
            } else {
                $unsortedAnnotation->setLevel('a' . $unsortedAnnotation->getLevel());
            }
            
            $sortedAnnotations[] = $unsortedAnnotation;
            $level              += 1;
        }

        usort($sortedAnnotations, function($a, $b) {
            return ($a->getLevel() < $b->getLevel() ? -1 : 1);
        });
        
        $level       = 1;
        $annotations = [];
        foreach ($sortedAnnotations as $annotation) {
            
            $annotation->setLevel($level++);
            $annotations[] = $annotation;
        }

        return $annotations;
    }
}