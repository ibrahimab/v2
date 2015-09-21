<?php
namespace AppBundle\Service\File;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.6
 * @since   0.0.6
 */
class Theme extends FileService
{
    /**
     * @var string
     */
    protected $collection = 'themes';
    
    /**
     * @param integer $id
     * @return array
     */
    public function getImage($id)
    {
        $result = $this->mongo->find(['file_id' => $id])
                              ->sort(['rank' => 1])
                              ->limit(1);
        dump(count($result));
        return count($result) > 0 ? (array)$result : null;
    }
}