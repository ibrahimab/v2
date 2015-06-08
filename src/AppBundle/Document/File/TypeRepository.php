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
	public function getMainImage(TypeServiceEntityInterface $type)
	{
		return $this->findOneBy(['file_id' => $type->getId(), 'kind' => TypeService::MAIN_IMAGE, 'rank' => 1]);
	}

	/**
	 * {@InheritDoc}
	 */
	public function getImages(TypeServiceEntityInterface $type)
	{
		return $this->findBy(['file_id' => $type->getId()]);
	}
    
    public function getSearchImages($types)
    {
        $ids = array_map(function(TypeServiceEntityInterface $type) {
            return $type->getId();
        }, $types);
        
        return $this->findBy(['file_id' => ['$in' => $ids], 'kind' => TypeService::SEARCH_IMAGE]);
    }
}