<?php
namespace AppBundle\Tests\Unit\Service\Api;
use		  AppBundle\Service\Api\User\UserService;
use		  AppBundle\Service\Api\Type\TypeService;

class UserServiceTest extends \Codeception\TestCase\Test
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
     * @var \AppBundle\Service\Api\User\UserService
     */
    protected $userService;
    
    /**
     * @var \AppBundle\Service\Api\Type\TypeService
     */
    protected $typeService;
    
    /**
     * @var \AppBundle\Service\Api\User\UserServiceDocumentInterface
     */
    protected $user;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->userService      = $this->serviceContainer->get('app.api.user');
		$this->typeService		= $this->serviceContainer->get('app.api.type');
        $this->user             = $this->userService->get('test_user_' . rand(1, 500));
    }

    protected function _after()
    {
        $this->serviceContainer = null;
        $this->userService      = null;
		$this->typeService      = null;
        $this->user             = null;
    }

    public function testGetUser()
    {
        $this->assertInstanceOf('AppBundle\Service\Api\User\UserServiceDocumentInterface', $this->user);
    }

	public function testGetFavorites()
	{
        $this->assertInstanceOf('AppBundle\Service\Api\User\UserServiceDocumentInterface', $this->user);
        $this->assertInternalType('array', $this->user->getFavorites());
	}
	
	public function testCountFavorites()
	{
        $this->assertInstanceOf('AppBundle\Service\Api\User\UserServiceDocumentInterface', $this->user);
        $this->assertEquals(count($this->user->getFavorites()), $this->user->totalFavorites());
	}
    
    public function testGetViewed()
    {
        $this->assertInstanceOf('AppBundle\Service\Api\User\UserServiceDocumentInterface', $this->user);
        $this->assertInternalType('array', $this->user->getViewed());
    }
    
    public function testCountViewed()
    {
        $this->assertInstanceOf('AppBundle\Service\Api\User\UserServiceDocumentInterface', $this->user);
        $this->assertEquals(count($this->user->getViewed()), $this->user->totalViewed());
    }
}