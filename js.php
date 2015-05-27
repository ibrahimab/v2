<?php

function get($collection, $key, $default = null)
{
    if (is_null($key)) {
        return $collection;
    }
    if (!is_object($collection) && isset($collection[$key])) {
        return $collection[$key];
    }
    // Crawl through collection, get key according to object or not
    foreach (explode('.', $key) as $segment) {
        // If object
        if (is_object($collection)) {
            if (!isset($collection->{$segment})) {
                return $default instanceof Closure ? $default() : $default;
            } else {
                $collection = $collection->$segment;
            }
            // If array
        } else {
            if (!isset($collection[$segment])) {
                return $default instanceof Closure ? $default() : $default;
            } else {
                $collection = $collection[$segment];
            }
        }
    }
    return $collection;
}

var_dump(get([
    
    'a' => [
        
        'b' => [
            
            'c'  => 1,
            'd'  => [
                'e' => 2,
            ],
        ],
    ],
    
], 'a.b.d.e'));


exit;

use AppBundle\Service\Javascript\JavascriptService;

require 'vendor/autoload.php';

$js = new JavascriptService([
    'attribute' => [
        'subattribute' => 'value',
    ]
]);

$js->getWithDot('attribute.subattribute');
