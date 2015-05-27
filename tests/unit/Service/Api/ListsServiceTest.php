<?php
namespace AppBundle\Tests\Unit\Service\Api;

class ListsServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->listService      = $this->serviceContainer->get('service.api.list');
    }

    protected function _after()
    {
        $this->listService = null;
    }
}