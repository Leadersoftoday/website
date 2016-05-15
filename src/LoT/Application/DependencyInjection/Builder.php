<?php
namespace LoT\Application\DependencyInjection;

use Pimple;

class Builder
{
    /**
     * @param array $config
     * @return Pimple
     */
    public function build(array $config, Pimple $container = null)
    {
        $container = $container ?: new Pimple;
        
        foreach ($config as $key => $signature) {
            $container->offsetSet(
                $key, 
                Pimple::share(
                    $this->getCallback($container, $signature)
                )
            );
        }
        
        return $container;
    }
    
    /**
     * @param SilexApplication $container
     * @param array $signature
     * @return Closure
     */
    private function getCallback(Pimple $container, array $signature)
    {
        $argumentBuilder = $this->getArgumentBuilder($container, $signature);
        
        return function() use ($argumentBuilder, $signature, $container) {            
            $computedArguments = $argumentBuilder($signature);
            
            if (! isset($signature['factory'])) {
                $class = $signature['class'];
                return new $class(...$computedArguments);
            }
            
            list($factory, $method) = $signature['factory'];

            if (strpos($factory, '@') === 0) {
                $factoryObject = $container->offsetGet(substr($factory, 1));
                return call_user_func_array(array($factoryObject, $method), $computedArguments);
            }

            return call_user_func_array(array($factory, $method), $computedArguments);
            
        };
    }
    
    /**
     * @param SilexApplication $container
     * @param array $signature
     * @return Closure
     */
    private function getArgumentBuilder(Pimple $container, array $signature)
    {
        return function($signature) use ($container) {
            $computedArguments = array();
            
            if (isset($signature['arguments'])) {
                foreach ($signature['arguments'] as $argument) {
                    if (strpos($argument, '@') === 0) {
                        $computedArguments[] = $container->offsetGet(substr($argument, 1));
                    } else {
                        $computedArguments[] = $argument;
                    }
                }
            }
            
            return $computedArguments;
        };
    }
}
