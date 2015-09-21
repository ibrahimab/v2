<?php
namespace AppBundle\Service\File;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.6
 * @since   0.0.6
 */
class Type extends FileService
{
    /**
     * @var string
     */
    protected $collection = 'types';
    
    /**
     * @param integer $typeId
     * @return array|null
     */
    public function getMainImage($typeId)
    {
        return $this->mongo->findOne(['file_id' => $typeId, 'type' => 'big']);
    }
    
    /**
     * @param integer $typeId
     * @return MongoCursor
     */
    public function getMainImages($typeId)
    {
        return $this->mongo->find([
            
            'file_id' => $typeId, 
            'type'    => [
                
                '$exists' => true, 
                '$in'     => ['big', 'small_above', 'small_below']
            ]
            
        ])->sort(['rank' => 1]);
    }
    
    /**
     * @param integer $typeId
     * @return MongoCursor
     */
    public function getImages($typeId)
    {
        return $this->mongo->find(['file_id' => $typeId])
                           ->sort(['rank'    => 1]);
    }
    
    /**
     * @param array $ids
     * @return MongoCursor
     */
    public function getSearchImages($typeIds)
    {
        return $this->mongo->find(['file_id' => ['$in' => $typeIds],
                                   'type'    => 'big'])
                           ->sort(['rank'    => 1]);
    }
}