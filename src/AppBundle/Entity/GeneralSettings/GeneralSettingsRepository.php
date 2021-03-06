<?php
namespace AppBundle\Entity\GeneralSettings;

use       AppBundle\Concern\SeasonConcern;
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
     * @const integer
     */
    const MONITOR_DATABASE_ID = 1;

    /**
     * {@InheritDoc}
     */
    public function getNewsletters()
    {

        $qb   = $this->createQueryBuilder('d');
        $expr = $qb->expr();

        $qb->select('partial d.{id, winterNewsletters}')
           ->andWhere('d.id=1');

        $results = $qb->getQuery()->getResult()[0]->getWinterNewsletters();

        $newslettersArray=preg_split("@\n@", $results);

        foreach ($newslettersArray as $key => $value) {
            if(preg_match("/^([0-9]+) (.*)$/",$value,$regs)) {
                $newslettersResults["http://chaletnl.m16.mailplus.nl/archief/mailing-".$regs[1].".html"] = $regs[2];
            }
        }

       return $newslettersResults;
    }

    /**
     * {@InheritDoc}
     */
    public function getSearchFormMessageSearchWithoutDates()
    {

        $seasonType  = ($this->getSeason() === SeasonConcern::SEASON_SUMMER ? "Summer" : "Winter");

        $qb   = $this->createQueryBuilder('d');
        $expr = $qb->expr();

        $qb->select('partial d.{id, searchFormMessageSearchWithoutDates'.$seasonType.'}')
           ->andWhere('d.id=1');

        $callFunction = 'getSearchFormMessageSearchWithoutDates'.$seasonType;

        $results = $qb->getQuery()->getResult()[0]->$callFunction();

        return $results;
    }

    /**
     * {@InheritDoc}
     */
    public function monitorDatabase()
    {
        $record = $this->find(['id' => self::MONITOR_DATABASE_ID]);
        $hash   = time() . '-' . md5('monitor-database-' . rand(1, 1000));

        $record->setMonitorMySQL($hash);

        $em = $this->getEntityManager();
        $em->persist($record);
        $em->flush();
        $em->clear();

        $record         = $this->find(['id' => self::MONITOR_DATABASE_ID]);
        $comparisonHash = $record->getMonitorMySQL();

        return $hash === $comparisonHash;
    }

    /**
     * {@InheritDoc}
     */
    public function getNoPriceShowUnavailable()
    {

        $seasonType  = ($this->getSeason() === SeasonConcern::SEASON_SUMMER ? "summer" : "winter");
        $fieldName = $seasonType . 'NoPriceShowUnavailable';
        $callFunction = 'get' . ucfirst($fieldName);

        $qb   = $this->createQueryBuilder('d');
        $expr = $qb->expr();

        $qb->select('partial d.{id, ' . $fieldName . '}')
           ->andWhere('d.id=1');

        return $qb->getQuery()->getResult()[0]->$callFunction();

    }

}
