<?php
namespace AppBundle\Service\Api\Type;

use       AppBundle\Service\Api\Type\TypeServiceRepositoryInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;

class TypeService
{
    /**
     * @var TypeServiceRepositoryInterface
     */
    public $typeRepository;

    /**
     * Constructor
     *
     * @param TypeServiceRepositoryInterface $typeRepository
     */
    public function __construct(TypeServiceRepositoryInterface $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    /**
     * Get all types
     * The options array accepts a 'where', 'order' and 'limit' key that customizes
     * the query
     *
     * @param  array $options
     * @return TypeServiceEntityInterface[]
     */
    public function all($options = [])
    {
        return $this->typeRepository->all($options);
    }

    /**
     * Get a single type
     * The options array accepts a 'where', 'order' and 'limit' key that customizes
     *
     * @param  array $by
     * @return TypeServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->typeRepository->find($by);
    }

    /**
     * Get types by place
     *
     * @param  PlaceServiceEntityInterface $place
     * @param  integer $limit
     * @return TypeServiceEntityInterface[]
     */
    public function findByPlace(PlaceServiceEntityInterface $place, $limit)
    {
        return $this->typeRepository->findByPlace($place, $limit);
    }

    /**
     * Counting all the types of all the accommodations for place provided
     *
     * @param PlaceServiceEntityInterface $place
     * @return integer
     */
    public function countByPlace(PlaceServiceEntityInterface $place)
    {
        return $this->typeRepository->countByPlace($place);
    }

    /**
     * Counting all the types of all the accommodations for region provided
     *
     * @param RegionServiceEntityInterface $region
     * @return integer
     */
    public function countByRegion(RegionServiceEntityInterface $region)
    {
        return $this->typeRepository->countByRegion($region);
    }

    /**
     * Counting all the types of all the accommodations for all the regions provided
     *
     * @param RegionServiceEntityInterface[] $regions
     * @return array
     */
    public function countByRegions($regions)
    {
        return $this->typeRepository->countByRegions($regions);
    }

    /**
     * Getting type and its associations in one query by ID
     *
     * @param integer $typeId
     * @return TypeServiceEntityInterface
     */
    public function findById($typeId)
    {
        return $this->typeRepository->findById($typeId);
    }

    /**
     * @param integer $typeId
     *
     * @return array
     */
    public function getTypeById($typeId)
    {
        return $this->typeRepository->getTypeById($typeId);
    }

    /**
     * @param TypeServiceEntityInterface $type
     *
     * @return array
     */
    public function transformEntityToArray(TypeServiceEntityInterface $type, $locale)
    {
        $accommodation = $type->getAccommodation();
        $place         = $accommodation->getPlace();
        $region        = $place->getRegion();
        $country       = $place->getCountry();

        if (in_array(null, [$accommodation, $place, $region, $country])) {
            throw new \Exception('Could not find accommodation, place, region or country for type = '. $type->getId());
        }

        return [

            'type_id'               => $type->getId(),
            'accommodation_id'      => $accommodation->getId(),
            'country_code'          => $country->getCountryCode(),
            'accommodation_name'    => $accommodation->getLocaleName($locale),
            'type_name'             => $type->getLocaleName($locale),
            'place_name'            => $place->getLocaleName($locale),
            'region_name'           => $region->getLocaleName($locale),
            'country_name'          => $country->getLocaleName($locale),
            'accommodation_kind'    => $accommodation->getKindIdentifier(),
            'type_quality'          => $type->getQuality(),
            'accommodation_quality' => $accommodation->getQuality(),
            'optimal_persons'       => $type->getOptimalResidents(),
            'max_persons'           => $type->getMaxResidents(),
        ];
    }
}
