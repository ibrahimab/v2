<?php
namespace AppBundle\Entity\Highlight;

use       AppBundle\Entity\Type\Type;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Highlight\HighlightServiceRepositoryInterface;
use       Doctrine\Common\Collections\Criteria;
use       Doctrine\ORM\Mapping\ClassMetadata;

/**
 * HighlightRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class HighlightRepository extends BaseRepository implements HighlightServiceRepositoryInterface
{
    /**
     * {@InheritDoc}
     */
    public function displayable($options = [], $datetime = null)
    {
        $order           = self::getOption($options, 'order',  'rank');
        $limit           = self::getOption($options, 'limit',  null);
        $offset          = self::getOption($options, 'offset', null);
        $resultsPerRow = self::getOption($options, 'results_per_row', null);
        $datetime = $datetime ?: new \DateTime('now');

        $qb       = $this->createQueryBuilder('h');
        $expr     = $qb->expr();

        $qb->select('partial h.{id, publishedAt, expiredAt}, partial t.{id, optimalResidents, maxResidents, quality}, partial a.{id, name, kind, quality}, partial p.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName}, partial r.{id, name, name, englishName, germanName, seoName, englishSeoName, germanSeoName}, partial c.{id, name, englishName, germanName, startCode}')
           ->leftJoin('h.type', 't')
           ->leftJoin('t.accommodation', 'a')
           ->leftJoin('a.place', 'p')
           ->leftJoin('p.region', 'r')
           ->leftJoin('p.country', 'c')
           ->where($expr->eq('h.display', ':display'))
           ->andWhere($this->publishedExpr('h', $expr))
           ->andWhere($this->activeWebsiteExpr('h', $expr))
           ->setParameters([

               'display' => true,
               'now'     => $datetime,
               'website'     => $this->getWebsite(),
           ])
           ->setMaxResults($limit)
           ->setFirstResult($offset)
           ->orderBy('h.' . $order);

        //
        // Return the correct number of results
        //

        // get number of results
        $results = $qb->getQuery()->execute();
        $numberOfResults = count($results);

        if ( $numberOfResults == $limit || $numberOfResults % $resultsPerRow == 0 ) {
          // full limit or $numberOfResults is divisible by $resultsPerRow
          return $results;
        } elseif($numberOfResults > $resultsPerRow ) {
          // incorrect number of results: slice the results to make it divisible by $resultsPerRow
          $numberOfResultsToReturn = floor($numberOfResults / $resultsPerRow) * $resultsPerRow;
          return array_slice($results, 0, $numberOfResultsToReturn);
        } else {
          // not enough results
          return array();
        }
    }
}
