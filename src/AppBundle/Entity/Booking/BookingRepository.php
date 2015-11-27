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
        ]);

        return $connection->lastInsertId();
    }

    /**
     * @param  integer $bookingId
     * @param  array   $insurances
     * @param  array   $options
     *
     * @return
     */
    public function saveOptions($bookingId, $insurances, $persons, $options)
    {
        $connection      = $this->getEntityManager()->getConnection();
        $bookingId       = (int)$bookingId;
        $bookingIdClause = [
            'boeking_id' => $bookingId,
        ];

        $connection->delete('boeking_persoon', $bookingIdClause);
        $connection->delete('boeking_optie', $bookingIdClause);
        $connection->update('boeking', ['schadeverzekering' => $insurances['damage']], $bookingIdClause);

        for ($i = 1; $i <= $persons; $i++) {

            $connection->insert('boeking_persoon', [

                'boeking_id'    => $bookingId,
                'persoonnummer' => $i,
                'status'        => 2,
            ]);
        }

        foreach ($options as $groupId => $optionGroup) {

            $number = 0;
            foreach ($optionGroup->parts as $partId => $option) {

                if ($option->amount > 0) {

                    for ($i = 1; $i <= $amount; $i++) {

                        $number += 1;
                        $connection->insert('boeking_optie', [

                            'boeking_id'         => $bookingId,
                            'optie_onderdeel_id' => $partId,
                            'persoonnummer'      => $number,
                            'status'             => 2,
                            'verkoop'            => $option->price,
                            'commissie'          => $option->commission,
                        ]);
                    }
                }
            }
        }
    }
}
