<?php
namespace AppBundle\Entity\Faq;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Faq\FaqServiceEntityInterface;
use       AppBundle\Service\Api\Faq\FaqServiceRepositoryInterface;
use       Doctrine\ORM\EntityManager;
use       Doctrine\ORM\NoResultException;

/**
 * Option repository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class FaqRepository extends BaseRepository implements FaqServiceRepositoryInterface
{
    /**
     * @return FaqServiceEntityInterface[]
     */
    public function getItems()
    {
        $qb   = $this->createQueryBuilder('f');
        $expr = $qb->expr();

        $qb->select('partial f.{id, question, englishQuestion, germanQuestion, answer, englishAnswer, germanAnswer}')
           ->where('f.websites LIKE :website')
           ->orderBy('f.order')
           ->setParameters([
               'website' => '%' . $this->getWebsite() . '%',
           ]);

        return $qb->getQuery()->getResult();
    }
}