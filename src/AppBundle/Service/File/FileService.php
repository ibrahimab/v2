<?php
namespace AppBundle\Service\File;
use       AppBundle\Service\Mongo\MongoService;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.6
 * @since   0.0.6
 */
class FileService
{
    /**
     * @const string
     */
    const DEFAULT_COLLECTION = 'files';
    
    /**
     * @var string
     */
    protected $collection = self::DEFAULT_COLLECTION;
    
    /**
     * @param MongoService $mongo
     */
    public function __construct(MongoService $mongo, $database)
    {
        $this->mongo = clone $mongo;
        $this->mongo->setDatabase($database);
        $this->mongo->setCollection($this->collection);
    }
    
    /**
     * @param MongoCursor $cursor
     * @param array
     * @return array
     */
    public function parse($cursor, $images = null)
    {   
        if (null === $images) {
            
            $images = [
                
                'top' => [
                    
                    'big'   => null, 
                    'small' => [
                        
                        'above' => null, 
                        'below' => null
                    ]
                ], 
                'bottom' => [], 
                'rest'   => [],
                'under'  => [],
            ];
        }
        
        foreach ($cursor as $file) {
            
            if (in_array($file['type'], ['big', 'small_above', 'small_below'])) {
            
                switch ($file['type']) {
                
                    case 'big':
                        $images['top']['big'] = $file;
                    break;
                
                    case 'small_above':
                        $images['top']['small']['above'] = $file;
                    break;
                
                    case 'small_below':
                        $images['top']['small']['below'] = $file;
                    break;
                }
                
            } else {
                
                if (isset($file['under']) && true === $file['under']) {
                    
                    $images['under'][] = $file;
                    
                } else {
                
                    if (count($images['bottom']) >= 2) {
                        $images['rest'][]   = $file;
                    } else {
                        $images['bottom'][] = $file;
                    }
                }
            }
        }
        
        return $images;
    }
}