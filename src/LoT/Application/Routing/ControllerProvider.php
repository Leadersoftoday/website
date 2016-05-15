<?php
namespace LoT\Application\Routing;

use Silex\ServiceProviderInterface;
use Silex\Application as SilexApplication;
use Pimple;
use Silex\ControllerCollection;

class ControllerProvider implements ServiceProviderInterface
{
    /** @var Pimple */
    private $di;
    
    /** @var array */
    private $config;

    /*
     * @param Pimple $di
     * @param array $config
     */
    public function __construct(Pimple $di, array $config)
    {
        $this->di = $di;
        $this->config = $config;
    }

    /**
     * @param SilexApplication $app
     */
    public function register(SilexApplication $app) 
    {
        $controllers = $app['controllers'];
        
        foreach ($this->config as $routeConfig) {
            $callback = $this->getCallbackForRoute($routeConfig);
            $methods = isset($routeConfig['method']) ? (array)$routeConfig['method'] : array('GET');
            
            foreach ($methods as $method) {
                $this->applyRoute($controllers, $routeConfig['route'], $method, $callback);
            }
        }
    }
    
    /**
     * @param array $routeConfig
     * @return Closure
     */
    private function getCallbackForRoute($routeConfig)
    {
        $di = $this->di;
        
        return function() use($di, $routeConfig) {
            $request = $di->offsetGet('http.request');
            $controller = $di->offsetGet($routeConfig['controller']);
            return call_user_func_array(array($controller, $routeConfig['action']), array($request));
        };
    }
    
    /**
     * @param ControllerCollection $controllers
     * @param string $route
     * @param string $method
     * @param Closure $callback
     * @throws \Exception
     */
    private function applyRoute(ControllerCollection $controllers, $route, $method, $callback)
    {
        switch ($method) {
            case "GET":
                $controllers->get($route, $callback);
                break;
            case "POST":
                $controllers->post($route, $callback);
                break;
            case "PUT":
                $controllers->put($route, $callback);
                break;
            case "DELETE":
                $controllers->delete($route, $callback);
                break;
            default:
                throw new \Exception("Cannot apply route for method: $method");
        }
    }

    /**
     * @param SilexApplication $app
     */
    public function boot(SilexApplication $app) 
    {
        //nothing to do here, just satisfying the interface
    }
}

/*
 * index:
 *   - route: "/"
 *   - method: "GET"
 *   - controller: controller.index
 */