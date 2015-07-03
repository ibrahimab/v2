<?php
namespace AppBundle\Old;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\OfferConcern;
use       AppBundle\Concern\AdditionalCostsConcern;
use       Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configuration class
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class Configuration
{
    /**
     * @var array
     */
    protected $config;
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $path            = dirname(dirname(dirname(__FILE__))) . '/old-classes';
        $realPath        = readlink($path);
        $unixDir         = dirname(dirname($realPath));
        
        $website         = $container->get('app.concern.website');
        $locale          = $container->get('request')->getLocale();
        $this->container = $container;
        $translator      = $container->get('translator');
        
        $this->config    = [
            
            'path'                            => '/chalet/',
            'seizoentype'                     => $this->container->get('app.concern.season')->get(),
            'website'                         => $website->get(),
            'websitetype'                     => $website->type(),
            'websitenaam'                     => $website->name(),
            'wederverkoop'                    => $website->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE),
            'livechat_code'                   => $website->getConfig(WebsiteConcern::WEBSITE_CONFIG_CHAT),
            'taal'                            => $locale,
            'reserveringskosten'              => 20,
            'lokale_testserver'               => false,
            'chalettour_aanpassing_commissie' => null,
            'ttv'                             => $website->getLanguageField(),
            'unixdir'                         => $unixDir,
            'soortaccommodatie'               => [
                
                1  => $translator->trans('type.kind.chalet'),
                2  => $translator->trans('type.kind.apartment'),
                3  => $translator->trans('type.kind.hotel'),
                4  => $translator->trans('type.kind.chalet-apartment'),
                6  => $translator->trans('type.kind.holiday-house'),
                7  => $translator->trans('type.kind.villa'),
                8  => $translator->trans('type.kind.castle'),
                9  => $translator->trans('type.kind.holiday-park'),
                10 => $translator->trans('type.kind.agriturismo'),
                11 => $translator->trans('type.kind.domain'),
                12 => $translator->trans('type.kind.pension'),
            ],
            'wt_htmlentities_cp1252'          => false,
            'basehref'                        => (true === $container->getParameter('ssl_enabled') ? 'https://' : 'http://') . $website->domain(),
            'seizoentype_namen'               => [
                
                SeasonConcern::SEASON_WINTER => $translator->trans('season.winter'),
                SeasonConcern::SEASON_SUMMER => $translator->trans('season.summer'),
            ],
            'aanbieding_soort'                => [
                
                OfferConcern::OFFER_NORMAL      => $translator->trans('offer.normal'),
                OfferConcern::OFFER_LAST_MINUTE => $translator->trans('offer.last-minute'),
            ],
            'aanbiedinginfo_binnen_cms'       => false,
            'temp_error_geen_tarieven'        => null,
            'bk_borg_soort'                   => [
                
                AdditionalCostsConcern::ADDITIONAL_COSTS_CASH            => $translator->trans('additional.costs.cash'),
                AdditionalCostsConcern::ADDITIONAL_COSTS_CREDITCARD      => $translator->trans('additional.costs.creditcard'),
                AdditionalCostsConcern::ADDITIONAL_COSTS_CASH_CREDITCARD => $translator->trans('additional.costs.cash.creditcard'),
                AdditionalCostsConcern::ADDITIONAL_COSTS_NOT_APPLICABLE  => $translator->trans('additional.costs.not.applicable'),
                AdditionalCostsConcern::ADDITIONAL_COSTS_AMOUNT_UNKNOWN  => $translator->trans('additional.costs.amount.unknown'),
                AdditionalCostsConcern::ADDITIONAL_COSTS_PAID_IN_ADVANCE => $translator->trans('additional.costs.paid.in.advance'),
            ],
            'bk_borg_soort_cms'               => [],
            'bk_eenheid'                      => [
                
                AdditionalCostsConcern::UNIT_PER_STAY     => $translator->trans('additional.costs.unit.per.stay'),
                AdditionalCostsConcern::UNIT_PER_PERSON   => $translator->trans('additional.costs.unit.per.person'),
                AdditionalCostsConcern::UNIT_PER_DAY      => $translator->trans('additional.costs.unit.per.day'),
                AdditionalCostsConcern::UNIT_EACH         => $translator->trans('additional.costs.unit.each'),
                AdditionalCostsConcern::UNIT_PER_KWH      => $translator->trans('additional.costs.unit.per.kwh'),
                AdditionalCostsConcern::UNIT_PER_LITER    => $translator->trans('additional.costs.unit.per.liter'),
                AdditionalCostsConcern::UNIT_PER_SET      => $translator->trans('additional.costs.unit.per.set'),
                AdditionalCostsConcern::UNIT_PER_WEEK     => $translator->trans('additional.costs.unit.per.week'),
                AdditionalCostsConcern::UNIT_PER_BAG      => $translator->trans('additional.costs.unit.per.bag'),
                AdditionalCostsConcern::UNIT_PER_TIME     => $translator->trans('additional.costs.unit.per.time'),
                AdditionalCostsConcern::UNIT_PER_HOUR     => $translator->trans('additional.costs.unit.per.hour'),
                AdditionalCostsConcern::UNIT_CUBIC_METERS => $translator->trans('additional.costs.unit.per.cubic.meters'),
            ],
            'bk_eenheid_cms'                  => [],
            'bk_ter_plaatse'                  => [
                
                $translator->trans('additional.costs.paid.in.advance'),
                $translator->trans('additional.costs.paid.locally'),
            ],
            'bk_ter_plaatse_cms'              => [],
            'bk_verplicht'                    => [
                
                $translator->trans('additional.costs.optional'),
                $translator->trans('additional.costs.mandatory'),
                $translator->trans('additional.costs.by.usage'),
                $translator->trans('additional.costs.by.tenant'),
            ],
            'bk_inclusief'                    => [
                
                $translator->trans('additional.costs.excluding'),
                $translator->trans('additional.costs.including'),
            ],
            'tmp_info_tonen'                  => null,
            'mustlogin'                       => false,
            'isMobile'                        => false,
            'voorkant_cms'                    => false,
        ];
    }
    
    /**
     * Magic getter function
     *
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->config)) {
            return $this->config[$name];
        }
        
        throw new \Exception(sprintf('Configuration key does not exist: (%s)', $name));
    }
}