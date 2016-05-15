<?php

namespace Spec\LoT\Application\View;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Twig_Environment as Twig;
use Twig_Template as Template;

class FactorySpec extends ObjectBehavior
{
    function let(Twig $twig)
    {
        $this->beConstructedWith($twig);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('LoT\Application\View\Factory');
    }
    
    function it_can_build_a_view(Twig $twig, Template $template)
    {
        $name = 'index.twig';
        $context = array('foo' => 'bar');
        
        $twig->loadTemplate($name)->shouldBeCalled()->willReturn($template);
        
        $view = $this->build($name, $context);
        $view->shouldHaveType('LoT\Application\View\View');
        $view->getTemplate()->shouldBe($template);
        $view->getContext()->shouldBe($context);
    }
}
