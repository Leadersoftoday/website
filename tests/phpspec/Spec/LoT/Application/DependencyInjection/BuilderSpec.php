<?php

namespace Spec\LoT\Application\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Pimple;

class BuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('LoT\Application\DependencyInjection\Builder');
    }
    
    function it_can_build_from_array()
    {
        $config = array(
            //standard object construction
            'epoch' => array(
                'class' => '\DateTimeImmutable',
                'arguments' => array(
                    'date' => '1981-07-15 03:11:00'
                )
            ),
            //using another service as a factory
            'seven' => array(
                'factory' => array('@epoch', 'modify'),
                'arguments' => array(
                    '+7 years'
                )
            ),
            //using a static method as a factory
            'twelve' => array(
                'factory' => array('\DateTime', 'createFromFormat'),
                'arguments' => array(
                    'Y-m-d H:i:s',
                    '1993-07-15 03:11:00'
                )
            ),
            //using another service as a parameter
            'immutable_twelve' => array(
                'factory' => array('\DateTimeImmutable', 'createFromMutable'),
                'arguments' => array('@twelve')
            )
        );
        
        $result = $this->build($config);
        
        $result->offsetGet('epoch')->shouldBeLike(new \DateTimeImmutable('1981-07-15 03:11:00'));
        $result->offsetGet('seven')->shouldBeLike(new \DateTimeImmutable('1988-07-15 03:11:00'));
        $result->offsetGet('twelve')->shouldBeLike(new \DateTime('1993-07-15 03:11:00'));
        $result->offsetGet('immutable_twelve')->shouldBeLike(new \DateTimeImmutable('1993-07-15 03:11:00'));
    }
}
