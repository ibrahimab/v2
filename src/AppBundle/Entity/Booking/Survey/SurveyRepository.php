<?php
namespace AppBundle\Entity\Booking\Survey;

use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use       AppBundle\Service\Api\Booking\Survey\SurveyServiceRepositoryInterface;
use       Doctrine\ORM\EntityRepository;

/**
 * SurveyRepository
 */
class SurveyRepository extends BaseRepository implements SurveyServiceRepositoryInterface
{
    public function allByType(TypeServiceEntityInterface $type)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();

        $ratings = [
            'ratingAccommodationReception',
            'ratingAccommodationLocation',
            'ratingAccommodationComfort',
            'ratingAccommodationCleaning',
            'ratingAccommodationFacilities',
            'ratingAccommodationPriceQuality',
            'ratingAccommodationTotal',
        ];

        $qb->select('s AS survey, partial t.{id}, partial a.{id}, partial b.{id, exactArrivalAt}, s.' . implode(', s.', $ratings) . '')
           ->leftJoin('s.type', 't')
           ->leftJoin('t.accommodation', 'a')
           ->leftJoin('s.booking', 'b')
           ->where($expr->eq('t', ':type'))
           ->orderBy('b.exactArrivalAt DESC, b.id')
           ->groupBy('s.booking')
           ->setParameters([
               'type' => $type,
           ]);

        $results  = $qb->getQuery()->useResultCache(true)->useQueryCache(true)->getResult();
        $surveys  = [];
        $averages = 0.0;

        foreach ($results as $result) {

            foreach ($ratings as $rating) {
                if ($result[$rating] > 0) {
                    $rating_total[$rating] += $result[$rating];
                    $rating_count[$rating]++;
                }
            }

            $surveys[] = $result['survey'];

        }

        $accommodationRatings = [];

        foreach ($ratings as $rating) {
            if (!empty($rating_count[$rating])) {
                $accommodationRatings[$rating] = round($rating_total[$rating] / $rating_count[$rating] , 1);
            }
        }

        return ['surveys' => $surveys, 'averageRatings' => $accommodationRatings];
    }

    /**
     * {@InheritDoc}
     */
    public function statsByType(TypeServiceEntityInterface $type)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('COUNT(s.booking) AS surveyCount, AVG(s.ratingAccommodationTotal) AS surveyAverageOverallRating')
                    ->leftJoin('s.type', 't')
                    ->leftJoin('t.accommodation', 'a')
                    ->where($expr->eq('t', ':type'))
                    ->andWhere($expr->gt('s.ratingAccommodationTotal', ':ratingAccommodationTotal'))
                    ->andWhere($expr->eq('s.reviewed', ':reviewed'))
                    ->andWhere($expr->eq('t.display', ':display'))
                    ->andWhere($expr->eq('a.display', ':display'))
                    ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
                    ->setParameters([

                        'type'         => $type,
                        'ratingAccommodationTotal' => 0,
                        'reviewed'     => 1,
                        'display'      => 1,
                        'weekendSki'   => false,
                    ])
                    ->getQuery();

        return $query->getSingleResult();
    }

    /**
     * {@InheritDoc}
     */
    public function statsByTypes($types)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('s.typeId, COUNT(s.booking) AS surveyCount, AVG(s.ratingAccommodationTotal) AS surveyAverageOverallRating')
                    ->leftJoin('s.type', 't')
                    ->leftJoin('t.accommodation', 'a')
                    ->where($qb->expr()->in('s.type', ':types'))
                    ->andWhere($expr->gt('s.ratingAccommodationTotal', ':ratingAccommodationTotal'))
                    ->andWhere($expr->eq('s.reviewed', ':reviewed'))
                    ->andWhere($expr->eq('t.display', ':display'))
                    ->andWhere($expr->eq('a.display', ':display'))
                    ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
                    ->setParameters([

                        'types'        => $types,
                        'ratingAccommodationTotal' => 0,
                        'reviewed'     => 1,
                        'display'      => 1,
                        'weekendSki'   => false,
                    ])
                    ->groupBy('s.type')
                    ->getQuery();

        return $query->getArrayResult();
    }

    /**
     * {@InheritDoc}
     */
    public function statsByPlace(PlaceServiceEntityInterface $place)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('t.id as typeId, COUNT(s.booking) AS surveyCount, AVG(s.ratingAccommodationTotal) AS surveyAverageOverallRating')
                    ->innerJoin('s.type', 't')
                    ->innerJoin('t.accommodation', 'a')
                    ->innerJoin('a.place', 'p')
                    ->where($expr->eq('p', ':place'))
                    ->andWhere($expr->gt('s.ratingAccommodationTotal', ':ratingAccommodationTotal'))
                    ->andWhere($expr->eq('s.reviewed', ':reviewed'))
                    ->andWhere($expr->eq('t.display', ':display'))
                    ->andWhere($expr->eq('a.display', ':display'))
                    ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
                    ->setParameters([

                        'place'        => $place,
                        'ratingAccommodationTotal' => 0,
                        'reviewed'     => 1,
                        'display'      => 1,
                        'weekendSki'   => false,
                    ])
                    ->groupBy('t.id')
                    ->getQuery();

        return $query->getArrayResult();
    }

    /**
     * {@InheritDoc}
     */
    public function statsByRegion(RegionServiceEntityInterface $region)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('COUNT(s.booking) AS surveyCount, AVG(s.ratingAccommodationTotal) AS surveyAverageOverallRating')
                    ->innerJoin('s.type', 't')
                    ->innerJoin('t.accommodation', 'a')
                    ->innerJoin('a.place', 'p')
                    ->where($expr->eq('p.region', ':region'))
                    ->andWhere($expr->gt('s.ratingAccommodationTotal', ':ratingAccommodationTotal'))
                    ->andWhere($expr->eq('s.reviewed', ':reviewed'))
                    ->andWhere($expr->eq('t.display', ':display'))
                    ->andWhere($expr->eq('a.display', ':display'))
                    ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
                    ->setParameters([

                        'region'       => $region,
                        'ratingAccommodationTotal' => 0,
                        'reviewed'     => 1,
                        'display'      => 1,
                        'weekendSki'   => false,
                    ])
                    ->getQuery();

        return $query->getSingleResult();
    }

    /**
     * {@InheritDoc}
     */
    public function statsByCountry(CountryServiceEntityInterface $country)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('COUNT(s.booking) AS surveyCount, AVG(s.ratingAccommodationTotal) AS surveyAverageOverallRating')
                    ->innerJoin('s.type', 't')
                    ->innerJoin('t.accommodation', 'a')
                    ->innerJoin('a.place', 'p')
                    ->where($expr->eq('p.country', ':country'))
                    ->andWhere($expr->gt('s.ratingAccommodationTotal', ':ratingAccommodationTotal'))
                    ->andWhere($expr->eq('s.reviewed', ':reviewed'))
                    ->andWhere($expr->eq('t.display', ':display'))
                    ->andWhere($expr->eq('a.display', ':display'))
                    ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
                    ->setParameters([

                        'country'      => $country,
                        'ratingAccommodationTotal' => 0,
                        'reviewed'     => 1,
                        'display'      => 1,
                        'weekendSki'   => false,
                    ])
                    ->getQuery();

        return $query->getSingleResult();
    }
}
