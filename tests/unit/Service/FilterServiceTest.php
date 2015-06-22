<?php
namespace AppBundle\Tests\Unit\Service;
use       AppBundle\Service\FilterService;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class FilterServiceTest extends \Codeception\TestCase\Test
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
     * @var FilterService
     */
    protected $filterService;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->filterService = new FilterService();
    }
    
    protected function _after()
    {
        $this->filterService = null;
    }
    
    public function testDistanceFilters()
    {
        $this->assertEquals(FilterService::TOKEN_DISTANCE_BY_SLOPE, $this->filterService->tokenize(FilterService::FILTER_DISTANCE, FilterService::FILTER_DISTANCE_BY_SLOPE));
        $this->assertEquals(FilterService::TOKEN_DISTANCE_MAX_250,  $this->filterService->tokenize(FilterService::FILTER_DISTANCE, FilterService::FILTER_DISTANCE_MAX_250));
        $this->assertEquals(FilterService::TOKEN_DISTANCE_MAX_500,  $this->filterService->tokenize(FilterService::FILTER_DISTANCE, FilterService::FILTER_DISTANCE_MAX_500));
        $this->assertEquals(FilterService::TOKEN_DISTANCE_MAX_1000, $this->filterService->tokenize(FilterService::FILTER_DISTANCE, FilterService::FILTER_DISTANCE_MAX_1000));
    }
    
    public function testLengthFilters()
    {
        $this->assertEquals(FilterService::TOKEN_LENGTH_MAX_100, $this->filterService->tokenize(FilterService::FILTER_LENGTH, FilterService::FILTER_LENGTH_MAX_100));
        $this->assertEquals(FilterService::TOKEN_LENGTH_MIN_100, $this->filterService->tokenize(FilterService::FILTER_LENGTH, FilterService::FILTER_LENGTH_MIN_100));
        $this->assertEquals(FilterService::TOKEN_LENGTH_MIN_200, $this->filterService->tokenize(FilterService::FILTER_LENGTH, FilterService::FILTER_LENGTH_MIN_200));
        $this->assertEquals(FilterService::TOKEN_LENGTH_MIN_400, $this->filterService->tokenize(FilterService::FILTER_LENGTH, FilterService::FILTER_LENGTH_MIN_400));
    }
    
    public function testFacilityFilters()
    {
        $this->assertEquals(FilterService::TOKEN_FACILITY_CATERING,      $this->filterService->tokenize(FilterService::FILTER_FACILITY, FilterService::FILTER_FACILITY_CATERING));
        $this->assertEquals(FilterService::TOKEN_FACILITY_INTERNET_WIFI, $this->filterService->tokenize(FilterService::FILTER_FACILITY, FilterService::FILTER_FACILITY_INTERNET_WIFI));
        $this->assertEquals(FilterService::TOKEN_FACILITY_SWIMMING_POOL, $this->filterService->tokenize(FilterService::FILTER_FACILITY, FilterService::FILTER_FACILITY_SWIMMING_POOL));
        $this->assertEquals(FilterService::TOKEN_FACILITY_SAUNA,         $this->filterService->tokenize(FilterService::FILTER_FACILITY, FilterService::FILTER_FACILITY_SAUNA));
        $this->assertEquals(FilterService::TOKEN_FACILITY_PRIVATE_SAUNA, $this->filterService->tokenize(FilterService::FILTER_FACILITY, FilterService::FILTER_FACILITY_PRIVATE_SAUNA));
        $this->assertEquals(FilterService::TOKEN_FACILITY_PETS_ALLOWED,  $this->filterService->tokenize(FilterService::FILTER_FACILITY, FilterService::FILTER_FACILITY_PETS_ALLOWED));
        $this->assertEquals(FilterService::TOKEN_FACILITY_FIREPLACE,     $this->filterService->tokenize(FilterService::FILTER_FACILITY, FilterService::FILTER_FACILITY_FIREPLACE));
    }
    
    public function testThemeFilters()
    {
        $this->assertEquals(FilterService::TOKEN_THEME_KIDS,               $this->filterService->tokenize(FilterService::FILTER_THEME, FilterService::FILTER_THEME_KIDS));
        $this->assertEquals(FilterService::TOKEN_THEME_CHARMING_PLACES,    $this->filterService->tokenize(FilterService::FILTER_THEME, FilterService::FILTER_THEME_CHARMING_PLACES));
        $this->assertEquals(FilterService::TOKEN_THEME_10_FOR_APRES_SKI,   $this->filterService->tokenize(FilterService::FILTER_THEME, FilterService::FILTER_THEME_10_FOR_APRES_SKI));
        $this->assertEquals(FilterService::TOKEN_THEME_SUPER_SKI_STATIONS, $this->filterService->tokenize(FilterService::FILTER_THEME, FilterService::FILTER_THEME_SUPER_SKI_STATIONS));
        $this->assertEquals(FilterService::TOKEN_THEME_WINTER_WELLNESS,    $this->filterService->tokenize(FilterService::FILTER_THEME, FilterService::FILTER_THEME_WINTER_WELLNESS));
    }
}