<?php
namespace AppBundle\Service\File;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.6
 * @since   0.0.6
 */
class Country extends FileService
{
    /**
     * @var string
     */
    protected $collection = 'countries';

    /**
     * @param integer $id
     * @return array
     */
    public function getImage($id)
    {
        return $this->mongo->findOne(['file_id' => $id,
                                      'rank'    => 1]);
    }
}