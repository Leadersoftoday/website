<?php
namespace LoT\Application\View;

use Twig_Template as Template;

class View
{
    /** @var Template */
    private $template;
    
    /** @var array */
    private $context;
    
    /**
     * @param Template $template
     * @param array $context
     */
    public function __construct(Template $template, array $context = array())
    {
        $this->template = $template;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->template->render($this->context);
    }

    /**
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }
}
