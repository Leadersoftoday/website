<?php
namespace LoT\Application\Config\Loader;

interface LoaderInterface
{
    public function loadFromFile($filename);
}
