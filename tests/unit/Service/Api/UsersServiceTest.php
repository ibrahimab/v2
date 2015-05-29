<?php
namespace AppBundle\Tests\Unit\Service\Api;
use		  AppBundle\Service\Api\User\UserService;
use		  AppBundle\Service\Api\Type\TypeService;

class UsersServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

	/**
	 * @var ListingService
	 */
	protected $listingService;

	/**
	 * @var TypeService
	 */
	protected $typeService;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->userService      = $this->serviceContainer->get('service.api.user');
		$this->typeService		= $this->serviceContainer->get('service.api.type');
    }

    protected function _after()
    {
        $this->userService = null;
		$this->typeService = null;
    }

    public function testGetUser()
    {
        $user = $this->userService->get('test_user');
        dump($user);
    }

	public function testGetFavorites()
	{
		$limit = rand(1, 10);
	}
	
	public function testCountFavorites()
	{
        // $total = $this->listingService->countFavorites('test_user');
        // $this->assertGreaterThan(0, $total);
	}
}