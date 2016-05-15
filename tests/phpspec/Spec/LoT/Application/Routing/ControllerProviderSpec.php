<?php
namespace Spec\LoT\Application\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Silex\ServiceProviderInterface;
use Pimple;
use Silex\Application as SilexApplication;

class ControllerProviderSpec extends ObjectBehavior
{
    function let(
        Pimple $di
    ) {
        $config = array();
        $this->beConstructedWith($di, $config);
    }
    
    
    function it_is_initializable()
    {
        $this->shouldHaveType('LoT\Application\Routing\ControllerProvider');
        $this->shouldHaveType('Silex\ServiceProviderInterface');
    }
}
