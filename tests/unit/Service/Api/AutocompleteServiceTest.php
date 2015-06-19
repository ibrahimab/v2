<?php
// namespace AppBundle\Tests\Unit\Service\Api;
// use       AppBundle\Service\Api\Autocomplete\AutocompleteService;
//
// class AutocompleteServiceTest extends \Codeception\TestCase\Test
// {
//     /**
//      * @var \UnitTester
//      */
//     protected $tester;
//
//     /**
//      * @var \appTestDebugProjectContainer
//      */
//     protected $serviceContainer;
//
//     /**
//      * @var \AppBundle\Service\Api\AutocompleteService
//      */
//     protected $autocompleteService;
//
//     protected function _before()
//     {
//         $this->serviceContainer    = $this->getModule('Symfony2')->container;
//         $this->autocompleteService = $this->serviceContainer->get('service.api.autocomplete');
//
//         // clearing doctrine
//         $this->serviceContainer->get('doctrine')->getManager()->clear();
//     }
//
//     protected function _after()
//     {
//         $this->serviceContainer    = null;
//         $this->autocompleteService = null;
//     }
//
//     /**
//      * @expectedException \AppBundle\Service\Api\Autocomplete\AutocompleteServiceException
//      */
//     public function testAutocompleteInvalidKind()
//     {
//         $this->autocompleteService->search('invalid-kind', ['invalid-kind']);
//     }
//
//     public function testAutocompleteOnlyAccommodations()
//     {
//         $limit   = rand(1, 5);
//         $kinds   = [AutocompleteService::KIND_ACCOMMODATION => true];
//         $results = $this->autocompleteService->search('Accommodation', array_keys($kinds), ['limit' => $limit]);
//
//         $this->assertCount($limit, $results);
//
//         $kindsFound = [];
//         foreach ($results as $result) {
//
//             $this->assertArrayHasKey('type', $result);
//             $this->assertArrayHasKey('type_id', $result);
//             $kindsFound[$result['type']] = true;
//         }
//
//         $this->assertEquals($kinds, $kindsFound);
//     }
//
//     public function testAutocompleteOnlyTypes()
//     {
//         $limit   = rand(1, 5);
//         $kinds   = [AutocompleteService::KIND_TYPE => true];
//         $results = $this->autocompleteService->search('Type', array_keys($kinds), ['limit' => $limit]);
//
//         $this->assertCount($limit, $results);
//
//         $kindsFound = [];
//         foreach ($results as $result) {
//
//             $this->assertArrayHasKey('type', $result);
//             $this->assertArrayHasKey('type_id', $result);
//             $kindsFound[$result['type']] = true;
//         }
//
//         $this->assertEquals($kinds, $kindsFound);
//     }
//
//     public function testAutocompleteOnlyCountries()
//     {
//         $limit   = rand(1, 5);
//         $kinds   = [AutocompleteService::KIND_COUNTRY => true];
//         $results = $this->autocompleteService->search('Country', array_keys($kinds), ['limit' => $limit]);
//
//         $this->assertCount($limit, $results);
//
//         $kindsFound = [];
//         foreach ($results as $result) {
//
//             $this->assertArrayHasKey('type', $result);
//             $this->assertArrayHasKey('type_id', $result);
//             $kindsFound[$result['type']] = true;
//         }
//
//         $this->assertEquals($kinds, $kindsFound);
//     }
//
//     public function testAutocompleteOnlyRegions()
//     {
//         $limit   = rand(1, 5);
//         $kinds   = [AutocompleteService::KIND_REGION => true];
//         $results = $this->autocompleteService->search('Region', array_keys($kinds), ['limit' => $limit]);
//
//         $this->assertCount($limit, $results);
//
//         $kindsFound = [];
//         foreach ($results as $result) {
//
//             $this->assertArrayHasKey('type', $result);
//             $this->assertArrayHasKey('type_id', $result);
//             $kindsFound[$result['type']] = true;
//         }
//
//         $this->assertEquals($kinds, $kindsFound);
//     }
//
//     public function testAutocompleteOnlyPlace()
//     {
//         $limit   = rand(1, 5);
//         $kinds   = [AutocompleteService::KIND_PLACE => true];
//         $results = $this->autocompleteService->search('Place', array_keys($kinds), ['limit' => $limit]);
//
//         $this->assertCount($limit, $results);
//
//         $kindsFound = [];
//         foreach ($results as $result) {
//
//             $this->assertArrayHasKey('type', $result);
//             $this->assertArrayHasKey('type_id', $result);
//             $kindsFound[$result['type']] = true;
//         }
//
//         $this->assertEquals($kinds, $kindsFound);
//     }
//
//     public function testAutocompleteAllKinds()
//     {
//         $limit   = rand(1, 10);
//         $kinds   = [AutocompleteService::KIND_ACCOMMODATION => true, AutocompleteService::KIND_TYPE   => true,
//                     AutocompleteService::KIND_COUNTRY       => true, AutocompleteService::KIND_REGION => true,
//                     AutocompleteService::KIND_PLACE            => true];
//
//         $results = $this->autocompleteService->search('', array_keys($kinds));
//
//         foreach ($kinds as $kind => $bool) {
//
//             $this->assertArrayHasKey($kind, $results);
//             //             $this->assertArrayHasKey('type', $results[$kind]);
//             //             $this->assertArrayHasKey('type_id', $result[$kind]);
//             // $this->assertCount($limit, $results[$kind]);
//         }
//     }
// }