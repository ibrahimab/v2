<?php
namespace AppBundle\Tests\Unit\Service\Api;
use		  AppBundle\Service\Api\Listing\ListingService;
use		  AppBundle\Service\Api\Type\TypeService;

class ListingsServiceTest extends \Codeception\TestCase\Test
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
        $this->listingService   = $this->serviceContainer->get('service.api.listing');
		$this->typeService		= $this->serviceContainer->get('service.api.type');
    }

    protected function _after()
    {
        $this->listService = null;
		$this->typeService = null;
    }

	public function testGetFavorites()
	{
		$limit	   = rand(1, 10);
		$favorites = $this->listingService->favorites('test_user', ['limit' => $limit]);

		$this->assertCount($limit, $favorites);
		$this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Listing\ListingServiceDocumentInterface', $favorites);
	}

	public function testGetFavorite()
	{
		$type = $this->typeService->find();
		$this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $type);

		$favorite = $this->listingService->favorite('test_user', $type);
		$this->assertInstanceOf('AppBundle\Service\Api\Listing\ListingServiceDocumentInterface', $favorite);
	}
}