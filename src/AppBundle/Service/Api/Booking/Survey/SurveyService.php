<?php
namespace AppBundle\Service\Api\Booking\Survey;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;

class SurveyService
{
    /**
     * @var SurveyServiceRepositoryInterface
     */
    public $surveyRepository;

    /**
     * Constructor
     *
     * @param SurveyServiceRepositoryInterface $surveyRepository
     */
    public function __construct(SurveyServiceRepositoryInterface $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

    /**
     * Get single survey by some criteria
     *
     * @param array $by
     * @return SurveyServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->surveyRepository->find($by);
    }

    /**
     * Get all the surveys based on criteria passed in
     *
     * @param array options
     * @return SurveyServiceEntityInterface[]
     */
    public function all($options = [])
    {
        return $this->surveyRepository->all($options);
    }

    /**
     * Get all the surveys based on type
     *
     * @param  TypeServiceEntityInterface $type
     * @return SurveyServiceEntityInterface[]
     */
    public function allByType(TypeServiceEntityInterface $type)
    {
        return $this->surveyRepository->allByType($type);
    }

    /**
     * Get all the reviewed surveys with a total accommodation rating, based on type
     *
     * @param  TypeServiceEntityInterface $type
     * @return SurveyServiceEntityInterface[]
     */
    public function allReviewedByType(TypeServiceEntityInterface $type)
    {
        $allByType = $this->surveyRepository->allByType($type);

        $reviewed = [];

        $ratingSum = 0;
        $ratingAccommodationTotal = $null;

        foreach ($allByType['surveys'] as $key => $value) {

            if ($value->getReviewed() == 1 && $value->getRatingAccommodationTotal() >= 1) {
                $reviewed[] = $value;
                $ratingSum += $value->getRatingAccommodationTotal();
            }
        }

        if (count($reviewed) > 0 ) {
            // calculate average
            $ratingAccommodationTotal = $ratingSum / count($reviewed);
        }

        return ['surveys' => $reviewed, 'averageRatings' => ['ratingAccommodationTotal' => $ratingAccommodationTotal]];

    }

    /**
     * @param TypeServiceEntityInterface $type
     * @return array
     */
    public function statsByType($type)
    {
        return $this->surveyRepository->statsByType($type);
    }

    /**
     * @param TypeServiceEntityInterfaces[] $types
     * @return array
     */
    public function statsByTypes($types)
    {
        return $this->surveyRepository->statsByTypes($types);
    }

    /**
     * @param PlaceServiceEntityInterface $place
     * @return array
     */
    public function statsByPlace(PlaceServiceEntityInterface $place)
    {
        return $this->surveyRepository->statsByPlace($place);
    }

    /**
     * @param RegionServiceEntityInterface $region
     * @return array
     */
    public function statsByRegion(RegionServiceEntityInterface $region)
    {
        return $this->surveyRepository->statsByRegion($region);
    }

    /**
     * @param CountryServiceEntityInterface $country
     * @return array
     */
    public function statsByCountry(CountryServiceEntityInterface $country)
    {
        return $this->surveyRepository->statsByCountry($country);
    }

    /**
     * @param integer $typeId
     * @param integer $page
     * @param integer $limit
     *
     * @return array
     */
    public function paginated($typeId, $page, $limit)
    {
        return $this->surveyRepository->paginated($typeId, $page, $limit);
    }

    /**
     * @param array $raw
     *
     * @return array
     */
    public function normalize($raw)
    {
        if (array_key_exists('surveyCount', $raw)) {

            // single record is passed in
            return [

                'count'   => intval($raw['surveyCount']),
                'average' => floatval($raw['surveyAverageOverallRating']),
            ];

        } else {

            // multiple records is passed in
            $records = [];

            foreach ($raw as $record) {

                $records[$record['typeId']] = [

                    'count'   => intval($record['surveyCount']),
                    'average' => floatval($record['surveyAverageOverallRating']),
                ];
            }

            return $records;
        }
    }
}
