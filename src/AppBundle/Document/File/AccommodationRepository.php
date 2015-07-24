<?php
namespace AppBundle\Document\File;
use		  AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;
use		  AppBundle\Service\Api\File\Accommodation\AccommodationService;
use		  AppBundle\Service\Api\File\Accommodation\AccommodationServiceRepositoryInterface;
use		  Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * AccommodationRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class AccommodationRepository extends DocumentRepository implements AccommodationServiceRepositoryInterface
{
	public function getMainImage($accommodation)
	{
        $id = $accommodation instanceof AccommodationServiceEntityInterface ? $accommodation->getId() : $accommodation;
		return $this->findOneBy(['file_id' => $id, 'kind' => AccommodationService::MAIN_IMAGE, 'rank' => 1]);
	}

	public function getImages($accommodation)
	{
        $id = $accommodation instanceof AccommodationServiceEntityInterface ? $accommodation->getId() : $accommodation;
		return $this->findBy(['file_id' => $id]);
	}

    public function getSearchImages($accommodations)
    {
        $ids = array_map(function($accommodation) {

            $id = $accommodation instanceof AccommodationServiceEntityInterface ? $accommodation->getId() : $accommodation;
            return $id;

        }, $accommodations);

        return $this->findBy(['file_id' => ['$in' => $ids], 'kind' => AccommodationService::SEARCH_IMAGE]);
    }
}