<?php
namespace Api;

class HighlightServiceTest extends \Codeception\TestCase\Test
{
    // BDD mixin
    use \Codeception\Specify;
    
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
        $this->highlightService = $this->serviceContainer->get('api.highlight.service');
    }

    // tests
    public function testGetHighlights()
    {
        $this->specify('Getting highlights', function() {
        
            $limit      = 5;
            $highlights = $this->highlightService->all(['limit' => $limit]);
            $total      = count($highlights);
            
            $this->assertInternalType('array', $highlights);
            $this->assertCount($limit, $highlights);
            $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface', $highlights);
        });
    }
    
    public function testGetHighlightBy()
    {
        $this->specify('Getting highlight by ID', function() {
    
            $highlight = $this->highlightService->find(['id' => 1]);
        
            $this->assertInstanceOf('AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface', $highlight);
            $this->assertEquals(1, $highlight->getId());
        });
    
        $this->specify('Getting highlight by Type ID', function() {
    
            $highlight = $this->highlightService->find(['typeId' => 1]);
        
            $this->assertInstanceOf('AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface', $highlight);
            $this->assertEquals(1, $highlight->getId());
        });
    }
    
    public function testNotFoundHighlights()
    {
        $this->specify('Getting null when non existing highlight is fetched', function() {
            
            $highlight = $this->highlightService->find(['id' => 'non-existant']);
            $this->assertNull($highlight);
        });
        
        $this->specify('Getting empty array when looking for highlights using a non-existant critera', function() {
            
            $highlights = $this->highlightService->all(['where' => ['id' => 'non-existant']]);
            $this->assertInternalType('array', $highlights);
            $this->assertEmpty($highlights);
        });
    }
    
    public function testGetDisplayableHighlights()
    {
        $this->specify('Getting displayable highlights (in fixtures there are 6 loaded)', function() {
            
            /**
             * The first fixtures loaded and tagged as displayable is expired
             * this is to test whether published/expired fields are respected
             * So the service should only return 5 items
             */
            $now        = new \DateTime('now');
            $highlights = $this->highlightService->displayable(['limit' => 6]);
            $this->assertNotEmpty($highlights);
            $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Highlight\HighlightServiceEntityInterface', $highlights);
            
            foreach ($highlights as $highlight) {
                
                // check if the published date is not null
                $this->assertNotNull($highlight->getPublishedAt(), 'Published date is null');
                
                // published date has to be in the past or right now
                $this->assertTrue($highlight->getPublishedAt() <= $now, 'published date (' . $highlight->getPublishedAt()->format('Y-m-d') . ') is not in the past or right now (' . $now->format('Y-m-d') . ')');
                
                // expired date has to be in the future if set
                if (null !== $highlight->getExpiredAt()) {
                    $this->assertTrue($highlight->getExpiredAt() > $now, 'Expired date (' . $highlight->getExpiredAt()->format('Y-m-d') .') was not past now (' . $now->format('Y-m-y'));
                }
            }
            
            // we asked for 6, but got 5 back. See above for why
            $this->assertCount(5, $highlights);
        });
    }
}