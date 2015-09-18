<?php
namespace AppBundle\Service\File;

/**
 * Accommodation file service
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.6
 * @since   0.0.6
 */
class Accommodation extends FileService
{
    /**
     * @var string
     */
    protected $collection = 'accommodations';
    
    /**
     * @param integer $accommodationId
     * @return array|null
     */
    public function getMainImage($accommodationId)
    {
        return $this->mongo->findOne(['file_id' => $accommodationId, 'type' => 'big']);
    }
    
    /**
     * @param integer $accommodationId
     * @return MongoCursor
     */
    public function getMainImages($accommodationId)
    {
        return $this->mongo->find([
            
            'file_id' => $accommodationId, 
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
    public function getImages($accommodationId)
    {
        return $this->mongo->find(['file_id' => $accommodationId])
                           ->sort(['rank'    => 1]);
    }
    
    /**
     * @param array $ids
     * @return MongoCursor
     */
    public function getSearchImages($accommodationIds)
    {
        return $this->mongo->find(['file_id' => ['$in' => $accommodationIds],
                                   'type'    => 'big'])
                           ->sort(['rank'    => 1]);
    }
}