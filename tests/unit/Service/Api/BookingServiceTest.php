<?php
namespace AppBundle\Tests\Unit\Service\Api;

use       AppBundle\Service\Api\BookingService;

class BookingServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var \appTestDebugProjectContainer
     */
    protected $serviceContainer;
    
    /**
     * @var \AppBundle\Service\Api\BookingService
     */
    protected $bookingService;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->bookingService   = $this->serviceContainer->get('service.api.booking');
        
        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }
    
    protected function _after()
    {
        $this->bookingService   = null;
    }

    public function testGetBookings()
    {
        $limit    = 3;
        $bookings = $this->bookingService->all(['limit' => $limit]);
        
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Booking\BookingServiceEntityInterface', $bookings);
        $this->assertCount($limit, $bookings);
    }

    public function testGetBooking()
    {
        $booking = $this->bookingService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Booking\BookingServiceEntityInterface', $booking);
    }
}