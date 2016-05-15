<?php

namespace Spec\LoT\Application\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior
{
    private $initialData = array(
        'foo' => array(1, 2, 3), 
        'bar' => array('baz' => 'qux')
    );
    
    function let()
    {
        $this->beConstructedWith($this->initialData);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('LoT\Application\Config\Config');
    }
    
    function it_can_retrieve_a_section()
    {
        $this->get('foo')->shouldBe(array(1, 2, 3));
        $this->get('bar')->shouldBe(array('baz' => 'qux'));
        $this->get('mang')->shouldBe(null);
    }
    
    function it_can_be_cast_to_an_array()
    {
        $this->asArray()->shouldBe($this->initialData);
    }
    
    function it_can_merge_data()
    {
        $this->merge(array(
            'foo' => array(4, 5, 'abc' => 678), 
            'bar' => array('abc' => 789)
        ));
        
        $this->get('foo')->shouldBe(array(1, 2, 3, 4, 5, 'abc' => 678));
        $this->get('bar')->shouldBe(array('baz' => 'qux', 'abc' => 789));
        $this->get('mang')->shouldBe(null);
    }
}
