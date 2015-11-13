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
    public function create(BookEntity $booking, $persons, $weekend)
    {
        // opslaan van `boeking`
        // deleten van `boeking_persoon`
        // deleten van `boeking_optie`
        // opslaan van schadeverzekering (`boeking`)
        // opslaan van `boeking_persoon`
        // opslaan `boeking_optie`
        // updaten van `boeking_persoon`
        // insert boeking_optie

        $connection = $this->getEntityManager()->getConnection();
        $config     = $this->getConfig();
        $website    = $this->getWebsiteConcern();

        $connection->insert('boeking', [

            'calc'               => $booking->calc,
            'type_id'            => $booking->typeId,
            'seizoen_id'         => $booking->seasonId,
            'taal'               => $this->getLocale(),
            'aantalpersonen'     => $persons,
            'reserveringskosten' => $config['reservation_costs']),
            'website'            => $website->get(),
            'aankomstdatum'      => $weekend,
            'annuleringsverzekering_poliskosten'  => ..,
            'annuleringsverzekering_percentage_1' => ..,
            'annuleringsverzekering_percentage_2' => ..,
            'annuleringsverzekering_percentage_3' => ..,
            'annuleringsverzekering_percentage_4' => ..,
            'schadeverzekering_percentage'        => ..,
            'reisverzekering_poliskosten'         => ..,
            'verzekeringen_poliskosten'           => ..,
            'toonper'                             => ..,
            'wederverkoop'                        => ($website->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE) ? 1 : 0),
            'naam_accommodatie'                   => begincode . $booking->typeId . '-' . plaats . '-' . ucfirst(soortaccommodatie) . ' ' . naam_ap,
            'invuldatum'                          => new \DateTime()
            'leverancier_id'                      => leverancier_id,
            'valt_onder_bedrijf'                  => ..,
            'aanbetaling1_dagennaboeken'          => ..,
            'total_reissom_dagenvooraankomst'     => ..,
        ]);
    }
}
