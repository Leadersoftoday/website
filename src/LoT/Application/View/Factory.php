<?php
namespace LoT\Application\View;

use Twig_Environment as Twig;

class Factory
{
    /** @var Twig */
    private $twig;
    
    /**
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function build($templateName, array $context = array())
    {
        $template = $this->twig->loadTemplate($templateName);
        return new View($template, $context);
    }
}
