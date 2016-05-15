<?php
namespace LoT\Application;

use Silex\Application as SilexApplication;
use Symfony\Component\HttpFoundation\Request;
use LoT\Application\Config\Loader\Yaml;
use LoT\Application\Config\Config;
use LoT\Application\DependencyInjection\Builder as ContainerBuilder;
use LoT\Application\Routing\ControllerProvider;
use Twig_Loader_Filesystem as TwigLoader;

class Application
{
    /** @var SilexApplication */
    private $silexApplication;

    /** @var Yaml */
    private $yaml;
    
    /** @var Config */
    private $config;
    
    /** @var ContainerBuilder */
    private $containerBuilder;
    
    /** @var string */
    private $templatePath;
    
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
        
        if ($this->templatePath) {
            $container->offsetSet('twig.loader', new TwigLoader($this->templatePath));
            
            $this->silexApplication->error(function (\Exception $e, $code) use ($container) {
                $viewFactory = $container->offsetget('view.factory');
                switch ($code) {
                    case 404:
                        $view = $viewFactory->build('error/not-found.twig', array());
                        break;
                    default:
                        $view = $viewFactory->build('error/error.twig', array(
                            'exception' => $e,
                            'code' => $code
                        ));
                }

                return new \Symfony\Component\HttpFoundation\Response($view);
            });
        }
        
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
     * @param string $path
     * @throws \Exception
     */
    public function setTemplatePath($path)
    {
        if (! is_dir($path)) {
            throw new \Exception('Template directory does not exist.');
        }
        
        $this->templatePath = $path;
    }

    /**
     * @return SilexApplication
     */
    public function getSilexApplication()
    {
        return $this->silexApplication;
    }
}
