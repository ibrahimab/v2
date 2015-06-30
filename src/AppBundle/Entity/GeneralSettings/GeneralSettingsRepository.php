<?php
namespace AppBundle\Entity\GeneralSettings;

use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\GeneralSettings\GeneralSettingsServiceRepositoryInterface;

/**
 * GeneralSettingsRepository
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @since   0.0.1
 * @package Chalet
 */
class GeneralSettingsRepository extends BaseRepository implements GeneralSettingsServiceRepositoryInterface
{
    /**
     * Finding all previously sent newsletters
     *
     * @param array $options
     * @return array
     */
    public function getNewsletters()
    {

        $qb   = $this->createQueryBuilder('d');
        $expr = $qb->expr();

        $qb->select('partial d.{id, winterNewsletters}')
           ->where('d.id=1');

        $results = $qb->getQuery()->getResult()[0]->getWinterNewsletters();

        $newslettersArray=preg_split("@\n@", $results);

        foreach ($newslettersArray as $key => $value) {
            if(preg_match("/^([0-9]+) (.*)$/",$value,$regs)) {
                $newslettersResults["http://chaletnl.m16.mailplus.nl/archief/mailing-".$regs[1].".html"] = $regs[2];
            }
        }

       return $newslettersResults;
    }
}
