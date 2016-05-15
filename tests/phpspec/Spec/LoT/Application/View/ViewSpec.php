<?php

namespace Spec\LoT\Application\View;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Twig_Template as Template;

class ViewSpec extends ObjectBehavior
{
    function let(Template $template)
    {
        $this->beConstructedWith($template, array('foo' => 'bar'));
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('LoT\Application\View\View');
    }
    
    function it_renders_the_template_when_cast_to_a_string(Template $template)
    {
        $template->render(array('foo' => 'bar'))->willReturn('abc 123');
        $this->__toString()->shouldBe('abc 123');
    }
    
    function it_can_return_the_template(Template $template)
    {
        $this->getTemplate()->shouldBe($template);
    }
    
    function it_can_return_the_context()
    {
        $this->getContext()->shouldBe(array('foo' => 'bar'));
    }
}
