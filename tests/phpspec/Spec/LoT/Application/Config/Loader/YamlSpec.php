<?php

namespace Spec\LoT\Application\Config\Loader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class YamlSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('LoT\Application\Config\Loader\Yaml');
    }
    
    function it_can_parse_a_yaml_file()
    {
        $this->loadFromFile('tests/fixtures/config/config.yml')->shouldBe(array(
            'foo' => 'bar',
            'things' => array('first', 'second'),
            'items' => array(1, 2, 3)
        ));
    }
}
