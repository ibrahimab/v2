<?php
namespace AppBundle\Entity\Booking\Survey;

use AppBundle\Entity\BaseRepository;
use AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use AppBundle\Service\Api\Booking\Survey\SurveyServiceRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use PDO;

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
    public function statsByType($type)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('COUNT(s.booking) AS surveyCount, AVG(s.ratingAccommodationTotal) AS surveyAverageOverallRating')
                    ->leftJoin('s.type', 't')
                    ->leftJoin('t.accommodation', 'a')
                    ->where(($type instanceof TypeServiceEntityInterface ? $expr->eq('t', ':type') : $expr->eq('t.id', ':type')))
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

    /**
     * {@InheritDoc}
     */
    public function paginated($typeId, $offset, $limit)
    {
        $db     = $this->getEntityManager()->getConnection();
        $locale = $this->getLocale();

        $query = "SELECT COUNT(s.boeking_id) AS total, (
                      SELECT AVG(s2.vraag1_7)
                      FROM   boeking_enquete s2
                      WHERE  s2.type_id = :type_id
                      AND    s2.beoordeeld = 1
                      AND    s2.vraag1_7 > 0
                  ) AS average
                  FROM   boeking_enquete s
                  WHERE  s.type_id    = :type_id
                  AND    s.beoordeeld = 1";

        $statement = $db->prepare($query);
        $statement->bindValue('type_id', $typeId);
        $statement->execute();

        $result  = $statement->fetch();
        $total   = $result['total'];
        $average = $result['average'];

        $query = "SELECT s.boeking_id AS booking_id, s.aankomstdatum_exact AS exact_arrival_date,
                         s.vraag1_7 AS average, vraag1_1 AS question_1_1, vraag1_2 AS question_1_2,
                         vraag1_3 AS question_1_3, vraag1_4 AS question_1_4, vraag1_5 AS question_1_5,
                         vraag1_6 AS question_1_6, websitetekst_naam AS website_name,
                         websitetekst AS website_text, tekst_language AS text_language,
                         websitetekst_gewijzigd" . ('_' . $locale) . " AS website_text_modified
                  FROM   boeking_enquete s
                  WHERE  s.type_id    = :type_id
                  AND    s.beoordeeld = 1
                  ORDER BY invulmoment DESC
                  LIMIT  " . $offset . ", " . $limit;

        $statement = $db->prepare($query);
        $statement->bindValue('type_id', $typeId);
        $statement->execute();

        $results  = $statement->fetchAll(PDO::FETCH_ASSOC);
        $surveys  = [];

        foreach ($results as $result) {

            if ($result['text_language'] !== $locale) {
                $result['website_text'] = $result['website_text_modified'];
            }

            $result = [

                'booking_id'         => intval($result['booking_id']),
                'exact_arrival_date' => $result['exact_arrival_date'],
                'average'            => floatval($result['average']),
                'question_1_1'       => intval($result['question_1_1']),
                'question_1_2'       => intval($result['question_1_2']),
                'question_1_3'       => intval($result['question_1_3']),
                'question_1_4'       => intval($result['question_1_4']),
                'question_1_5'       => intval($result['question_1_5']),
                'question_1_6'       => intval($result['question_1_6']),
                'website_name'       => $result['website_name'],
                'text_language'      => $result['text_language'],
                'website_text'       => $result['website_text'],
            ];

            $surveys[] = $result;
        }

        return [

            'surveys' => $surveys,
            'total'   => intval($total),
            'average' => floatval($average),
            'offset'  => $offset + $limit,
        ];
    }
}