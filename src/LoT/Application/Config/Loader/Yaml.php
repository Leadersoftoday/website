<?php
namespace LoT\Application\Config\Loader;

use Symfony\Component\Yaml\Yaml as Parser;

class Yaml implements LoaderInterface
{
    /**
     * @param string $filename
     * @return array
     */
    public function loadFromFile($filename) 
    {
        return Parser::parse(file_get_contents($filename));
    }

}
