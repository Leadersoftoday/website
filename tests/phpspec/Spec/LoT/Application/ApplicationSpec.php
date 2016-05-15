<?php
namespace Spec\LoT\Application;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Silex\Application as SilexApplication;
use Symfony\Component\HttpFoundation\Request;
use LoT\Application\Config\Loader\Yaml;
use LoT\Application\Config\Config;
use LoT\Application\DependencyInjection\Builder as ContainerBuilder;
use Pimple;
use Silex\ControllerCollection;

class ApplicationSpec extends ObjectBehavior
{
    function let(
        SilexApplication $silexApplication, 
        Yaml $yaml, 
        Config $config, 
        ContainerBuilder $containerBuilder
    ) {
        $this->beConstructedWith($silexApplication, $yaml, $config, $containerBuilder);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('LoT\Application\Application');
    }
    
    function it_can_return_the_silex_application(SilexApplication $silexApplication)
    {
        $this->getSilexApplication()->shouldBe($silexApplication);
    }
    
    function it_can_be_configured_from_a_directory_of_yaml_files(Yaml $yaml, Config $config)
    {
        $dir = getcwd() . '/tests/fixtures/application_config';
        
        foreach (glob($dir . "/*.yml") as $path) {
            $ns = basename($path, '.yml');
            
            $data = array($ns => array('foo' => 'bar'));
            
            $yaml->loadFromFile($path)
                ->shouldBeCalled()
                ->willReturn($data);
            
            $config->merge($data)->shouldBeCalled();
        }
        
        $this->ingestConfigFromDirectory(getcwd() . '/tests/fixtures/application_config');
    }
    
    function it_can_be_run(
        SilexApplication $silexApplication, 
        Request $request,
        Config $config,
        ContainerBuilder $containerBuilder,
        Pimple $container,
        ControllerCollection $controllers
    ) {
        $silexApplication->offsetGet('controllers')->willReturn($controllers);
        $config->get('services')->willReturn(array('di'));
        $config->get('routes')->willReturn(array());
        $containerBuilder->build(array('di'))->shouldBeCalled()->willReturn($container);
        
        $silexApplication->run($request)->shouldBeCalled();
        $this->run($request);
    }
}
