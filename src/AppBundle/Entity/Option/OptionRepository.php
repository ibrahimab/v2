<?php
namespace AppBundle\Entity\Option;
use       AppBundle\Entity\BaseRepositoryTrait;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Service\Api\Option\OptionServiceRepositoryInterface;
use       Doctrine\ORM\EntityManager;
use       Doctrine\ORM\NoResultException;
use       Doctrine\ORM\Query;

/**
 * Option repository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class OptionRepository implements OptionServiceRepositoryInterface
{
    /**
     * @trait BaseRepositoryTrait
     */
    use BaseRepositoryTrait;

    /**
     * @const integer
     */
    const OPTION_GROUP_SEASON_SUMMER  = 369;

    /**
     * @const integer
     */
    const OPTION_GROUP_SEASON_DEFAULT = 42;

    /**
     * @var EntityManager
     */
    private $manager;


    /**
     * Constructor
     *
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->manager;
    }

    /**
     * @return string
     */
    public function getTravelInsurancesDescription()
    {
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();
        $groupId    = ($this->getSeason() === SeasonConcern::SEASON_SUMMER ? self::OPTION_GROUP_SEASON_SUMMER : self::OPTION_GROUP_SEASON_DEFAULT);

        $qb->select('g.optie_groep_id, g.omschrijving, g.omschrijving_en, g.omschrijving_de, s.optie_soort_id, s.reisverzekering')
           ->from('optie_groep', 'g')
           ->join('g', 'optie_soort', 's', 'g.optie_soort_id = s.optie_soort_id')
           ->where($expr->eq('s.reisverzekering', ':travelInsurance'))
           ->andWhere($expr->eq('g.optie_groep_id', ':groupId'))
           ->setParameters([

               'travelInsurance' => 1,
               'groupId'         => $groupId,
           ]);

        $statement   = $qb->execute();
        $result      = $statement->fetch();
        $description = null;

        if (false !== $result) {

            switch ($this->getLocale()) {

                case 'nl':
                    $description = $result['omschrijving'];
                break;

                case 'en':
                    $description = $result['omschrijving_en'];
                break;

                case 'de':
                    $description = $result['omschrijving_de'];
                break;
            }
        }

        return $description;
    }

    /**
     * @param  integer      $accommodationId
     * @param  integer|null $season
     * @param  integer|null $weekend
     *
     * @return array
     */
    public function options($accommodationId, $season = null, $weekend = null)
    {
        $website    = $this->getWebsiteConcern();
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();

        $qb->select('vo.optie_soort_id, vo.optie_groep_id, vo.snaam, vo.snaam_en, vo.snaam_de, vo.optie_onderdeel_id,
                     vo.onaam, vo.onaam_en, vo.onaam_de, s.omschrijving AS somschrijving, s.omschrijving_en AS somschrijving_en,
                     s.omschrijving_de AS somschrijving_de, g.omschrijving AS gomschrijving, g.omschrijving_en AS gomschrijving_en,
                     g.omschrijving_de AS gomschrijving_de')
           ->from('optie_accommodatie a, view_optie vo, optie_soort s, optie_onderdeel o, optie_groep g', '')
           ->where('a.optie_soort_id = vo.optie_soort_id')
           ->andWhere('a.optie_soort_id = s.optie_soort_id')
           ->andWhere('a.optie_groep_id = vo.optie_groep_id')
           ->andWhere('vo.optie_onderdeel_id = o.optie_onderdeel_id')
           ->andWhere('a.optie_groep_id = g.optie_groep_id')
           ->andWhere($expr->eq('a.accommodatie_id', ':accommodation'))
           ->andWhere('o.tonen_accpagina = 1')
           ->andWhere('o.actief = 1')
           ->orderBy('vo.svolgorde, vo.snaam, vo.ovolgorde, vo.onaam')
           ->setParameter('accommodation', $accommodationId);

        if ($website->getConfig(WebsiteConcern::WEBSITE_CONFIG_TRAVEL_INSURANCE) !== 1) {
            $qb->andWhere('s.reisverzekering = 0');
        }

        if (true === $website->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE)) {
            $qb->andWhere('s.beschikbaar_wederverkoop = 1');
        } else {
            $qb->andWhere('s.beschikbaar_directeklanten = 1');
        }

        $statement = $qb->execute();
        $locale    = $this->getLocale();
        $results   = $statement->fetchAll();
        $tree      = [];
        $parts     = [];

        foreach ($results as $result) {

            switch ($locale) {

                case 'en':

                    $kind = $result['snaam_en'];
                    $part = $result['onaam_en'];

                break;

                case 'de':

                    $kind = $result['snaam_de'];
                    $part = $result['onaam_de'];

                break;

                case 'nl':
                default:

                    $kind = $result['snaam'];
                    $part = $result['onaam'];
            }

            $description = $this->getDescription($locale, $result);

            if (!isset($tree[$result['optie_soort_id']])) {
                $tree[$result['optie_soort_id']] = ['name' => $kind, 'groupId' => $result['optie_groep_id'], 'description' => $description, 'parts' => []];
            }

            $tree[$result['optie_soort_id']]['parts'][$result['optie_onderdeel_id']] = [

                'id'        => $result['optie_onderdeel_id'],
                'name'      => $part,
            ];

            $parts[] = $result['optie_onderdeel_id'];
        }

        $qb = $connection->createQueryBuilder();

        $qb->select('ta.optie_onderdeel_id, ta.week, ta.verkoop')
           ->from('optie_tarief ta, optie_onderdeel o', '')
           ->where('ta.optie_onderdeel_id = o.optie_onderdeel_id')
           ->andWhere($expr->eq('ta.beschikbaar', ':available'))
           ->andWhere($expr->in('ta.optie_onderdeel_id', $parts))
           ->setParameter('available', 1);

        if (null !== $season) {

            $qb->andWhere($expr->eq('ta.seizoen_id', ':season'));
            $qb->setParameter('season', $season);
        }

        if (null === $weekend) {

            $qb->andWhere($expr->gte('ta.week', ':week'))
               ->setParameter('week' , time());

        } else {

            $qb->andWhere($expr->eq('ta.week', ':week'))
               ->setParameter('week', $weekend);
        }

        $statement = $qb->execute();
        $results   = $statement->fetchAll();
        $prices    = [];
        $cache     = [];

        foreach ($results as $result) {

            $result['verkoop'] = floatval($result['verkoop']);

            if (!isset($prices[$result['optie_onderdeel_id']])) {

                $prices[$result['optie_onderdeel_id']] = [

                    'prices' => [],
                    'price'  => $result['verkoop'],
                ];
            }

            $prices[$result['optie_onderdeel_id']]['prices'][] = $result['verkoop'];
        }

        foreach ($prices as $id => $data) {

            if (count(array_unique($data['prices'])) > 1) {
                $prices[$id] = false;
            } else {
                $prices[$id] = $data['price'];
            }
        }

        foreach ($tree as $kind => $data) {

            foreach ($data['parts'] as $id => $row) {

                if (isset($prices[$id])) {

                    $tree[$kind]['parts'][$id]['price']    = abs($prices[$id]);
                    $tree[$kind]['parts'][$id]['discount'] = ($prices[$id] < 0);
                    $tree[$kind]['parts'][$id]['free']     = ($prices[$id] === 0.0);

                } else {

                    unset($tree[$kind]['parts'][$id]);

                    if (count($tree[$kind]['parts']) === 0) {
                        unset($tree[$kind]);
                    }
                }
            }
        }

        return $tree;
    }

    /**
     * @param  integer $optionId
     *
     * @return string
     */
    public function option($optionId)
    {
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();

        $qb->select('s.naam, s.omschrijving AS somschrijving, s.omschrijving_en AS somschrijving_en, s.omschrijving_de AS somschrijving_de,
                     g.omschrijving AS gomschrijving, g.omschrijving_en AS gomschrijving_en, g.omschrijving_de AS gomschrijving_de')
           ->from('optie_soort s, optie_groep g', '')
           ->where('g.optie_soort_id = s.optie_soort_id')
           ->andWhere($expr->eq('g.optie_groep_id', ':optionId'))
           ->setParameter('optionId', $optionId);

        $statement   = $qb->execute();
        $result      = $statement->fetch();
        $locale      = $this->getLocale();
        $description = '';

        if (false !== $result) {
            $description = $this->getDescription($locale, $result);
        }

        return ['name' => $result['naam'], 'description' => $description];
    }

    /**
     * @param  integer $accomodationId
     * @param  integer $season
     * @param  integer $weekend
     */
    public function calculatorOptions($accommodationId, $season, $weekend)
    {
        $connection      = $this->getEntityManager()->getConnection();
        $qb              = $connection->createQueryBuilder();
        $expr            = $qb->expr();
        $locale          = $this->getLocale();
        $localeField     = ($locale === 'nl' ? '' : ('_' . $locale));
        $websiteConcern  = $this->getWebsiteConcern();
        $resale          = $websiteConcern->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE);
        $resaleField     = ($resale ? 's.beschikbaar_wederverkoop = 1' : 's.beschikbaar_directeklanten = 1');
        $travelInsurance = $websiteConcern->getConfig(WebsiteConcern::WEBSITE_CONFIG_TRAVEL_INSURANCE);

        $qb->select("s.optie_soort_id, s.algemeneoptie, s.naam_enkelvoud" . $localeField . " AS naam_enkelvoud,
                     s.omschrijving" . $localeField . " AS omschrijving, s.annuleringsverzekering, s.reisverzekering, s.gekoppeld_id")
           ->from('optie_soort s, optie_accommodatie a', '')
           ->andWhere($expr->eq(($resale ? 's.beschikbaar_wederverkoop' : 'beschikbaar_directeklanten'), 1))
           ->andWhere($expr->neq('naam_enkelvoud' . $localeField, ':empty'))
           ->andWhere($expr->eq('a.accommodatie_id', ':accommodationId'))
           ->andWhere('a.optie_soort_id = s.optie_soort_id')
           ->orderBy('s.volgorde, s.naam')
           ->setParameter('accommodationId', $accommodationId)
           ->setParameter('empty', '');

        $statement = $qb->execute();
        $results   = $statement->fetchAll();

        $qb        = $connection->createQueryBuilder();
        $expr      = $qb->expr();

        $options   = [];
        $kinds     = [];

        foreach ($results as $result) {

            if (!$result['reisverzekering'] || $travelInsurance) {

                $id           = $result['optie_soort_id'];
                $kinds[]      = (int)$id;
                $options[$id] = [

                    'naam_enkelvoud'         => ucfirst($result['naam_enkelvoud']),
                    'annuleringsverzekering' => $result['annuleringsverzekering'],
                    'reisverzekering'        => $result['reisverzekering'],
                    'onderdelen'             => [],
                ];
            }
        }

        $qb->select('o.naam' . $localeField . ' AS naam, s.optie_soort_id, o.optie_onderdeel_id, o.min_leeftijd, o.max_leeftijd, o.min_deelnemers,
                     o.actief, g.optie_groep_id, g.omschrijving' . $localeField . ' AS omschrijving, t.verkoop, t.wederverkoop_commissie_agent')
           ->from('optie_onderdeel o, optie_groep g, optie_tarief t, optie_soort s, optie_accommodatie a, seizoen sz', '')
           ->andWhere($expr->neq('o.naam' . $localeField, ':empty'))
           ->andWhere($expr->eq('o.te_selecteren', ':toSelect'))
           ->andWhere($expr->eq('o.te_selecteren_door_klant', ':toSelectCustomer'))
           ->andWhere($expr->eq('o.actief', ':active'))
           ->andWhere($expr->eq('sz.seizoen_id', ':season'))
           ->andWhere($expr->eq('a.accommodatie_id', ':accommodationId'))
           ->andWhere('a.optie_soort_id = s.optie_soort_id')
           ->andWhere('a.optie_groep_id = g.optie_groep_id')
           ->andWhere('t.optie_onderdeel_id = o.optie_onderdeel_id')
           ->andWhere('t.seizoen_id = sz.seizoen_id')
           ->andWhere($expr->eq('t.week', ':weekend'))
           ->andWhere($expr->eq('t.beschikbaar', ':available'))
           ->andWhere($expr->in('g.optie_soort_id', $kinds))
           ->andWhere('g.optie_soort_id = s.optie_soort_id')
           ->andWhere('g.optie_groep_id = o.optie_groep_id')
           ->orderBy('o.volgorde, o.naam')
           ->setParameters([

                'empty'            => '',
                'toSelect'         => 1,
                'toSelectCustomer' => 1,
                'active'           => 1,
                'season'           => $season,
                'accommodationId'  => $accommodationId,
                'weekend'          => $weekend,
                'available'        => 1,
           ]);

        $statement = $qb->execute();
        $results   = $statement->fetchAll();

        foreach ($results as $result) {

            $kindId = (int)$result['optie_soort_id'];
            $id     = (int)$result['optie_onderdeel_id'];

            $options[$kindId]['onderdelen'][$id] = [

                'naam'           => $result['naam'],
                'verkoop'        => $result['verkoop'],
                'commissie'      => $result['wederverkoop_commissie_agent'],
                'min_leeftijd'   => $result['min_leeftijd'],
                'max_leeftijd'   => $result['max_leeftijd'],
                'min_deelnemers' => $result['min_deelnemers'],
            ];

            if ($result['min_leeftijd'] || $result['max_leeftijd']) {
                $options[$kindId]['onderdelen'][$id]['leeftijdsgebonden'];
            }
        }

        return $options;
    }

    /**
     * @param integer $typeId
     *
     * @return array
     */
    public function datesOptions($typeId)
    {
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();

        $statement  = $qb->select('DISTINCT ta.week AS weekend, s.optie_soort_id AS option_kind_id')
                         ->from('optie_tarief ta, optie_onderdeel o, optie_accommodatie a, optie_soort s, type t', '')
                         ->andWhere('a.optie_soort_id = s.optie_soort_id')
                         ->andWhere('ta.optie_onderdeel_id = o.optie_onderdeel_id')
                         ->andWhere('t.accommodatie_id = a.accommodatie_id')
                         ->andWhere($expr->eq('t.type_id', ':typeId'))
                         ->andWhere('a.optie_groep_id = o.optie_groep_id')
                         ->setParameter('typeId', $typeId)
                         ->execute();

        $results    = $statement->fetchAll();
        $options    = [];

        foreach ($results as $result) {

            if (!isset($options[$result['weekend']])) {
                $options[$result['weekend']] = 0;
            }

            $options[$result['weekend']] += 1;
        }

        return $options;
    }

    /**
     * @param string $locale
     * @param array  $result
     *
     * @return string
     */
    private function getDescription($locale, $result)
    {
        switch ($locale) {

            case 'en':

                $description  = nl2br($result['somschrijving_en']);
                $description .= (trim($result['somschrijving_en']) !== '' ? '<br />' : '');
                $description .= nl2br($result['gomschrijving_en']);

            break;

            case 'de':

                $description  = nl2br($result['somschrijving_de']);
                $description .= (trim($result['somschrijving_de']) !== '' ? '<br />' : '');
                $description .= nl2br($result['gomschrijving_de']);

            break;

            case 'nl':
            default:

                $description  = nl2br($result['somschrijving']);
                $description .= (trim($result['somschrijving']) !== '' ? '<br />' : '');
                $description .= nl2br($result['gomschrijving']);
        }

        return $description;
    }
}
