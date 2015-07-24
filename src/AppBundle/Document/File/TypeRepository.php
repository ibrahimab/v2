<?php
namespace AppBundle\Document\File;
use		  AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use		  AppBundle\Service\Api\File\Type\TypeService;
use       AppBundle\Service\Api\File\Type\TypeServiceRepositoryInterface;
use		  Doctrine\ODM\MongoDB\DocumentRepository;

class TypeRepository extends DocumentRepository implements TypeServiceRepositoryInterface
{
	/**
	 * {@InheritDoc}
	 */
	public function getMainImage($type)
	{
        $id = ($type instanceof TypeServiceEntityInterface ? $type->getId() : $type);
		return $this->findOneBy(['file_id' => $id, 'kind' => TypeService::MAIN_IMAGE, 'rank' => 1]);
	}

	/**
	 * {@InheritDoc}
	 */
	public function getImages($type)
	{
        $id = ($type instanceof TypeServiceEntityInterface ? $type->getId() : $type);
		return $this->findBy(['file_id' => $id]);
	}

    public function getSearchImages($types)
    {
        $ids = array_map(function($type) {

            $id = ($type instanceof TypeServiceEntityInterface ? $type->getId() : $type);
            return $id;

        }, $types);

        return $this->findBy(['file_id' => ['$in' => $ids], 'kind' => TypeService::SEARCH_IMAGE]);
    }
}