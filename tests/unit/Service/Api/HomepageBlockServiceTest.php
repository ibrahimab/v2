<?php
namespace AppBundle\Tests\Unit\Service\Api;


class HomepageBlockServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->serviceContainer     = $this->getModule('Symfony2')->container;
        $this->homepageBlockService = $this->serviceContainer->get('service.api.homepageblock');
        
        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }
    
    protected function _after()
    {
        $this->homepageBlockService = null;
    }
    
    public function testGetHomepageBlock()
    {
        $limit          = 3;
        $homepageBlocks = $this->homepageBlockService->all(['limit' => $limit]);
        
        $this->assertInternalType('array', $homepageBlocks);
        $this->assertCount($limit, $homepageBlocks);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface', $homepageBlocks);
    }
    
    public function testGetFormattedTitle()
    {
        $homepageBlock = $this->homepageBlockService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface', $homepageBlock);
        
        // setting locale title
        $homepageBlock->setLocaleTitles([
            
            'nl' => 'Testing [font]font[/font] tag',
            'en' => 'Testing [font]font[/font] tag',
            'de' => 'Testing [font]font[/font] tag',
        ]);
        
        // testing tags
        $this->assertEquals('Testing <span class="styled">font</span> tag', $homepageBlock->getLocaleTitle('nl', ['attribute' => true]));
        $this->assertEquals('Testing <div id="div-style">font</div> tag',   $homepageBlock->getLocaleTitle('en', ['tag' => 'div', 'attribute' => ['name' => 'id', 'value' => 'div-style']]));
        $this->assertEquals('Testing <non-existing non-existing="non-existing">font</non-existing> tag',   $homepageBlock->getLocaleTitle('de', ['tag' => 'non-existing', 'attribute' => ['name' => 'non-existing', 'value' => 'non-existing']]));
        $this->assertEquals('Testing [font]font[/font] tag', $homepageBlock->getLocaleTitle('nl', false));
        $this->assertEquals('Testing <span>font</span> tag', $homepageBlock->getLocaleTitle('nl', ['attribute' => false]));
    }
    
    public function testGetPublishedBlocks()
    {
        $homepageBlocks = $this->homepageBlockService->published(['limit' => 3]);
        $this->assertCount(3, $homepageBlocks);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface', $homepageBlocks);
    }
}