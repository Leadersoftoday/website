<?php
namespace LoT\Application;

use Silex\Application as SilexApplication;
use Symfony\Component\HttpFoundation\Request;
use LoT\Application\Config\Loader\Yaml;
use LoT\Application\Config\Config;
use LoT\Application\DependencyInjection\Builder as ContainerBuilder;
use LoT\Application\Routing\ControllerProvider;

class Application
{
    /** @var SilexApplication */
    private $silexApplication;

    /** @var Yaml */
    private $yaml;
    
    /** @var Config */
    private $config;
    
    /**
     * @var ContainerBuilder 
     */
    private $containerBuilder;
    
    /**
     * @param SilexApplication $silexApplication
     * @param Yaml $yaml
     * @param Config $config
     * @param ContainerBuilder $containerBuilder
     */
    public function __construct(
        SilexApplication $silexApplication = null, 
        Yaml $yaml = null,
        Config $config = null,
        ContainerBuilder $containerBuilder = null
    ) {
        $this->silexApplication = $silexApplication ?: new SilexApplication();
        $this->yaml = $yaml ?: new Yaml();
        $this->config = $config ?: new Config();
        $this->containerBuilder = $containerBuilder ?: new ContainerBuilder();
    }

    /**
     * @param Request $request
     */
    public function run(Request $request = null)
    {
        $container = $this->containerBuilder->build((array)$this->config->get('services'));
        
        $request = $request ?: Request::createFromGlobals();
        $container->offsetSet('http.request', $request);
        
        $controllerProvider = new ControllerProvider($container, (array)$this->config->get('routes'));
        $controllerProvider->register($this->silexApplication);

        $this->silexApplication->run($request);
    }

    /**
     * @param string $path
     * @todo: abstract this so it can read config files in different formats (json, php array, etc)
     */
    public function ingestConfigFromDirectory($path)
    {
        if (! is_dir($path)) {
            throw new \Exception('Config directory does not exist.');
        }
        
        foreach (glob("$path/*.yml") as $file) {
            $this->config->merge((array)$this->yaml->loadFromFile($file));
        }
    }

    /**
     * @return SilexApplication
     */
    public function getSilexApplication()
    {
        return $this->silexApplication;
    }
}
