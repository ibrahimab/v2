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
            'seizoen_id'         => $booking->getSeasonId(),
            'taal'               => $this->getLocale(),
            'aantalpersonen'     => $booking->getPersons(),
            'reserveringskosten' => $config['reservation_costs'],
            'website'            => $website->get(),
            'aankomstdatum'      => $booking->getArrivalAt(),
            'annuleringsverzekering_poliskosten'  => '',
            'annuleringsverzekering_percentage_1' => $type['annuleringsverzekering_percentage_1'],
            'annuleringsverzekering_percentage_2' => $type['annuleringsverzekering_percentage_2'],
            'annuleringsverzekering_percentage_3' => $type['annuleringsverzekering_percentage_3'],
            'annuleringsverzekering_percentage_4' => $type['annuleringsverzekering_percentage_4'],
            'schadeverzekering_percentage'        => $type['schadeverzekering_percentage'],
            'reisverzekering_poliskosten'         => '',
            'verzekeringen_poliskosten'           => $type['verzekeringen_poliskosten'],
            'toonper'                             => $type['toonper'],
            'wederverkoop'                        => ($website->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE) ? 1 : 0),
            'naam_accommodatie'                   => $type['naam_ap'],
            'invuldatum'                          => time(),
            'leverancier_id'                      => $type['leverancierid'],
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
