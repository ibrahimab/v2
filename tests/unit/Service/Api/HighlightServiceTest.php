<?php
namespace Api;

class HighlightServiceTest extends \Codeception\TestCase\Test
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
     * @var \AppBundle\Service\Api\HighlightService
     */
    protected $highlightService;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->highlightService = $this->serviceContainer->get('service.api.highlight');
        
        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }
    
    protected function _after()
    {
        $this->highlightService = null;
    }

    public function testGetHighlights()
    {
            // Getting highlights
            $limit      = 5;
            $highlights = $this->highlightService->all(['limit' => $limit]);
            
            $this->assertInternalType('array', $highlights);
            $this->assertCount($limit, $highlights);
            $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface', $highlights);
    }

    public function testGetHighlightBy()
    {
            // $this->specify('Getting highlight by ID
            $highlight = $this->highlightService->find(['id' => 1]);
            
            $this->assertInstanceOf('AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface', $highlight);
            $this->assertEquals(1, $highlight->getId());

            // Getting highlight by Type ID
            $highlight = $this->highlightService->find();
            
            $this->assertInstanceOf('AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface', $highlight);
            $this->assertEquals(1, $highlight->getId());
    }

    public function testNotFoundHighlights()
    {
            // Getting null when non existing highlight is fetched
            $highlight = $this->highlightService->find(['id' => 'non-existant']);
            
            $this->assertNull($highlight);

            // Getting empty array when looking for highlights using a non-existant critera
            $highlights = $this->highlightService->all(['where' => ['id' => 'non-existant']]);
            
            $this->assertInternalType('array', $highlights);
            $this->assertEmpty($highlights);
    }

    public function testGetDisplayableHighlights()
    {
            // Getting displayable highlights (in fixtures there are 6 loaded)
            /**
             * The first fixtures loaded and tagged as displayable is expired
             * this is to test whether published/expired fields are respected
             * So the service should only return 5 items
             *
             * @TODO Mock the published/expired fields to let this test not depend on the fixtures being reloaded
             */
            $now        = new \DateTime('now');
            $highlights = $this->highlightService->displayable(['limit' => 6]);
            
            $this->assertNotEmpty($highlights);
            $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface', $highlights);
            
            foreach ($highlights as $highlight) {
            
                // check if the published date is not null
                $this->assertNotNull($highlight->getPublishedAt(), 'Published date is null ' . $highlight->getId());
            
                // published date has to be in the past or right now
                $this->assertTrue($highlight->getPublishedAt() <= $now, 'published date (' . $highlight->getPublishedAt()->format('Y-m-d') . ') is not in the past or right now (' . $now->format('Y-m-d') . '). Recheck the test database and if needed reload the fixtures.');
            
                // expired date has to be in the future if set
                if (null !== $highlight->getExpiredAt()) {
                    $this->assertTrue($highlight->getExpiredAt() > $now, 'Expired date (' . $highlight->getExpiredAt()->format('Y-m-d') .') was not past now (' . $now->format('Y-m-y') . '). Recheck the test database and if needed reload the fixtures.');
                }
            }

            // we asked for 6, but got 5 back. See above for why
            $this->assertCount(5, $highlights);
    }
    
    public function testHighlightWithType()
    {
            // Testing Highlight with type
            $highlight = $this->highlightService->find(['id' => 1]);

            $this->assertInstanceOf('AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface', $highlight);
            $this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $highlight->getType());
    }
}