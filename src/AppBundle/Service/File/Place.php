<?php
namespace AppBundle\Service\File;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.6
 * @since   0.0.6
 */
class Place extends FileService
{
    /**
     * @var string
     */
    protected $collection = 'places';
    
    /**
     * @param integer $id
     * @return array
     */
    public function getImage($id)
    {
        return $this->mongo->findOne(['file_id' => $id, 
                                      'rank'    => 1]);
    }
    
    /**
     * @param integer $id
     * @return array
     */
    public function getImages($id)
    {
        return $this->mongo->find(['file_id' => $id])
                           ->sort(['rank'    => 1]);
    }
}