<?php
namespace Service;

use       AppBundle\Service\Javascript\JavascriptService;

class JavascriptServiceTest extends \Codeception\TestCase\Test
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
     * @var AppBundle\Service\Javascript\JavascriptService
     */
    protected $javascriptService;

    protected function _before()
    {
        $this->javascriptService = new JavascriptService();
    }
    
    protected function _after()
    {
        $this->javascriptService = null;
    }
    
    public function testEmptyJavascriptOnInitialize()
    {
        $this->assertEquals([], $this->javascriptService->toArray());
    }
    
    public function testSetAndGetNewAttribute()
    {
        $this->javascriptService->set('new', 'attribute');
        $this->assertEquals('attribute', $this->javascriptService->get('new'));
    }
    
    /**
     * @expectedException \AppBundle\Service\Javascript\Exception\ExistsException
     */
    public function testExceptionSettingExistingAttribute()
    {
        $this->javascriptService->set('attribute', 'value');
        $this->javascriptService->set('attribute', 'new-value');
    }
    
    public function testOverrideAndGetAttribute()
    {
        $this->javascriptService->set('attribute', 'value');
        $this->assertEquals('value', $this->javascriptService->get('attribute'));
        
        $this->javascriptService->override('attribute', 'new-value');
        $this->assertEquals('new-value', $this->javascriptService->get('attribute'));
    }
    
    public function testAttributeExists()
    {
        $this->assertFalse($this->javascriptService->exists('non-existant'));
    }
    
    public function testPushToAttribute()
    {
        $this->javascriptService->set('attribute', []);
        $this->assertEquals([], $this->javascriptService->get('attribute'));
        
        $this->javascriptService->push('attribute', 'test');
        $this->assertEquals(['test'], $this->javascriptService->get('attribute'));
    }
    
    public function testPopFromAttribute()
    {
        $this->javascriptService->set('attribute', ['value']);
        $this->assertEquals(['value'], $this->javascriptService->get('attribute'));
        
        $value = $this->javascriptService->pop('attribute');
        $this->assertEquals('value', $value);
        $this->assertEquals([], $this->javascriptService->get('attribute'));
    }
    
    /**
     * @expectedException \AppBundle\Service\Javascript\Exception\NotFoundException
     */
    public function testAddToNonExistingAttribute()
    {
        $this->javascriptService->add('attribute', 'subattribute', 'subvalue');
    }
    
    public function testAddToAttribute()
    {
        $this->javascriptService->set('attribute', []);
        $this->javascriptService->add('attribute', 'subattribute', 'subvalue');
        $this->assertEquals(['subattribute' => 'subvalue'], $this->javascriptService->get('attribute'));
    }
    
    public function testRemoveFromAttribute()
    {
        $this->javascriptService->set('attribute', []);
        $this->javascriptService->add('attribute', 'subattribute', 'subvalue');
        $this->assertEquals(['subattribute' => 'subvalue'], $this->javascriptService->get('attribute'));
        
        $value = $this->javascriptService->remove('attribute', 'subattribute');
        $this->assertEquals([], $this->javascriptService->get('attribute'));
    }
    
    /**
     * @expectedException \AppBundle\Service\Javascript\Exception\NotFoundException
     */
    public function testRemoveNonExistingElementInAttribute()
    {
        $this->javascriptService->set('attribute', []);
        $this->javascriptService->remove('attribute', 'subattribute');
    }
    
    public function testGetWithDot()
    {
        $this->javascriptService->set('attribute', []);
        dump($this->javascriptService->getWithDot());
    }
}