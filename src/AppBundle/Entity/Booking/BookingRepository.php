<?php
namespace AppBundle\Entity\Booking;

use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Booking\BookingServiceRepositoryInterface;
use       AppBundle\Entity\Booking\Booking as BookingEntity;

/**
 * BookingRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class BookingRepository extends BaseRepository implements BookingServiceRepositoryInterface
{
    public function create($booking, $type)
    {
        $connection = $this->getEntityManager()->getConnection();
        $config     = $this->getConfig();
        $website    = $this->getWebsiteConcern();

        $booking    = $connection->insert('boeking', [

            'calc'               => $booking->getCalc(),
            'type_id'            => $booking->getTypeId(),
            'seizoen_id'         => $type['season'],
            'taal'               => $this->getLocale(),
            'aantalpersonen'     => $booking->getPersons(),
            'reserveringskosten' => $config['reservation_costs'],
            'website'            => $website->get(),
            'aankomstdatum'      => $booking->getArrivalAt(),
            'annuleringsverzekering_poliskosten'  => $type['insurances']['cancellation_insurance_policy_fee'],
            'annuleringsverzekering_percentage_1' => $type['insurances']['cancellation_percentages'][1],
            'annuleringsverzekering_percentage_2' => $type['insurances']['cancellation_percentages'][2],
            'annuleringsverzekering_percentage_3' => $type['insurances']['cancellation_percentages'][3],
            'annuleringsverzekering_percentage_4' => $type['insurances']['cancellation_percentages'][4],
            'schadeverzekering_percentage'        => $type['insurances']['damage_insurance_percentage'],
            'reisverzekering_poliskosten'         => $type['insurances']['travel_insurance_policy_fee'],
            'verzekeringen_poliskosten'           => $type['insurances']['insurances_policy_fee'],
            'toonper'                             => $type['show'],
            'wederverkoop'                        => ($website->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE) ? 1 : 0),
            'naam_accommodatie'                   => $type['begincode'] . $type['type_id'] . ' - ' . $type['name_place'] . ' - ' . $type['name_persons'],
            'invuldatum'                          => time(),
            'leverancier_id'                      => $type['supplier_id'],
            'valt_onder_bedrijf'                  => $website->getConfig(WebsiteConcern::WEBSITE_COMPANY),
            'aanbetaling1_dagennaboeken'          => $config['deposit_days_after_booking'],
            'totale_reissom_dagenvooraankomst'    => $config['total_travel_sum_days_before_arrival'],
            'accprijs'                            => $type[''],
        ]);

        return $connection->lastInsertId();
    }
}
