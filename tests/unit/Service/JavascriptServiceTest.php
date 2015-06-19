<?php
namespace AppBundle\Tests\Unit\Service;
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
     * @expectedException \AppBundle\Service\Javascript\Exception\NotFoundException
     */
    public function testNotExistingAttribute()
    {
        $this->javascriptService->get('non.existing.attribute');
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
        $a = [
            'b' => [
                
                'c' => ['d', 'e', 'f'],
                'g' => 'h',
                'i' => [
                    'j' => 'k',
                ]
            ],
            
            'l' => [
                
                'm' => 'n',
            ],
        ];
        
        $o = [
            
            'p' => 'q',
            'r' => ['s'],
        ];
        
        $t = 'u';
        
        $v = [
            
            'w' => [
                
                'x' => [
                    
                    'y' => 'z'
                ]
            ]
        ];
        
        $alphabetTree = [
            
            'a' => $a,
            'o' => $o,
            't' => $t,
            'v' => $v,
        ];
        
        $this->javascriptService->set('alphabetTree', $alphabetTree);
        
        // testing the complete tree
        $this->assertEquals($alphabetTree,     $this->javascriptService->get('alphabetTree'));
                                               
        // testing toplevel nodes              
        $this->assertEquals($a,                $this->javascriptService->get('alphabetTree.a'));
        $this->assertEquals($o,                $this->javascriptService->get('alphabetTree.o'));
        $this->assertEquals($t,                $this->javascriptService->get('alphabetTree.t'));
        $this->assertEquals($v,                $this->javascriptService->get('alphabetTree.v'));
                                               
        // second level nodes                  
        $this->assertEquals($a['b'],           $this->javascriptService->get('alphabetTree.a.b'));
        $this->assertEquals($a['l'],           $this->javascriptService->get('alphabetTree.a.l'));
        $this->assertEquals($o['p'],           $this->javascriptService->get('alphabetTree.o.p'));
        $this->assertEquals($o['r'],           $this->javascriptService->get('alphabetTree.o.r'));
        $this->assertEquals($v['w'],           $this->javascriptService->get('alphabetTree.v.w'));
                                               
        // third level nodes                   
        $this->assertEquals($a['b']['c'],      $this->javascriptService->get('alphabetTree.a.b.c'));
        $this->assertEquals($a['b']['g'],      $this->javascriptService->get('alphabetTree.a.b.g'));
        $this->assertEquals($a['b']['i'],      $this->javascriptService->get('alphabetTree.a.b.i'));
        $this->assertEquals($a['l']['m'],      $this->javascriptService->get('alphabetTree.a.l.m'));
        $this->assertEquals($o['r'][0],        $this->javascriptService->get('alphabetTree.o.r.0'));
        $this->assertEquals($v['w']['x'],      $this->javascriptService->get('alphabetTree.v.w.x'));
                                               
        // fourth level nodes                  
        $this->assertEquals($a['b']['c'][0],   $this->javascriptService->get('alphabetTree.a.b.c.0'));
        $this->assertEquals($a['b']['c'][1],   $this->javascriptService->get('alphabetTree.a.b.c.1'));
        $this->assertEquals($a['b']['c'][2],   $this->javascriptService->get('alphabetTree.a.b.c.2'));
        $this->assertEquals($a['b']['i']['j'], $this->javascriptService->get('alphabetTree.a.b.i.j'));
        $this->assertEquals($v['w']['x']['y'], $this->javascriptService->get('alphabetTree.v.w.x.y'));
    }
    
    public function testSetWithDot()
    {
        $a = [
            'b' => [
                
                'c' => ['d', 'e', 'f'],
                'g' => 'h',
                'i' => [
                    'j' => 'k',
                ]
            ],
            
            'l' => [
                
                'm' => 'n',
            ],
        ];
        
        $o = [
            
            'p' => 'q',
            'r' => ['s'],
        ];
        
        $t = 'u';
        
        $v = [
            
            'w' => [
                
                'x' => [
                    
                    'y' => 'z'
                ]
            ]
        ];
        
        $alphabetTree = [
            
            'a' => $a,
            'o' => $o,
            't' => $t,
            'v' => $v,
        ];
        // array
        $this->javascriptService->set('alphabetTree', []);
        
        // top level
        $this->javascriptService->set('alphabetTree.a', []);
        $this->javascriptService->set('alphabetTree.o', []);
        $this->javascriptService->set('alphabetTree.t', 'u');
        $this->javascriptService->set('alphabetTree.v', []);
        
        // second level nodes
        $this->javascriptService->set('alphabetTree.a.b', []);
        $this->javascriptService->set('alphabetTree.a.l', []);
        $this->javascriptService->set('alphabetTree.o.p', 'q');
        $this->javascriptService->set('alphabetTree.o.r', []);
        $this->javascriptService->set('alphabetTree.v.w', []);
        
        // third level nodes
        $this->javascriptService->set('alphabetTree.a.b.c', []);
        $this->javascriptService->set('alphabetTree.a.b.g', 'h');
        $this->javascriptService->set('alphabetTree.a.b.i', []);
        $this->javascriptService->set('alphabetTree.a.l.m', 'n');
        $this->javascriptService->set('alphabetTree.o.r.0', 's');
        $this->javascriptService->set('alphabetTree.v.w.x', []);
        
        // fourth level nodes
        $this->javascriptService->set('alphabetTree.a.b.c.0', 'd');
        $this->javascriptService->set('alphabetTree.a.b.c.1', 'e');
        $this->javascriptService->set('alphabetTree.a.b.c.2', 'f');
        $this->javascriptService->set('alphabetTree.a.b.i.j', 'k');
        $this->javascriptService->set('alphabetTree.v.w.x.y', 'z');
        
        $this->assertEquals($alphabetTree, $this->javascriptService->get('alphabetTree'));
    }
    
    public function testDeleteAttribute()
    {
        $this->javascriptService->set('delete-attribute', 1);
        $this->assertEquals(1, $this->javascriptService->get('delete-attribute'));
        
        $this->javascriptService->delete('delete-attribute');
        $this->assertFalse($this->javascriptService->exists('delete-attribute'));
    }
}