<?php
namespace AppBundle\Entity\Theme;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Theme\ThemeServiceRepositoryInterface;

/**
 * ThemeRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @version 0.0.5
 * @since   0.0.5
 * @package Chalet
 */
class ThemeRepository extends BaseRepository implements ThemeServiceRepositoryInterface
{
    /**
     * {@InheritDoc}
     */
    public function themes()
    {
        $qb   = $this->createQueryBuilder('th');
        $expr = $qb->expr();

        $qb->select('partial th.{id, name, englishName, germanName, url, englishUrl, germanUrl, externalUrl, englishExternalUrl, germanExternalUrl}')
           ->where($expr->eq('th.season', ':season'))
           ->andWhere($expr->eq('th.active', ':active'))
           ->andWhere($expr->neq('th.' . $this->getLocaleField('name'), ':name'))
           ->andWhere($expr->neq('th.' . $this->getLocaleField('url'), ':url'))
           ->setParameters([

               'season' => $this->getSeason(),
               'active' => 1,
               'name'   => '',
               'url'    => '',
           ]);

        return $qb->getQuery()->getResult();
    }
}