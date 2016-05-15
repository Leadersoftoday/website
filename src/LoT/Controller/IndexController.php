<?php
namespace LoT\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LoT\Application\View\Factory as ViewFactory;

class IndexController
{
    /** @var ViewFactory */
    private $viewFactory;
    
    /**
     * @param ViewFactory $viewFactory
     */
    public function __construct(ViewFactory $viewFactory) 
    {
        $this->viewFactory = $viewFactory;
    }
    
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $view = $this->viewFactory->build('index.twig', array('message' => 'Hello, world.'));
        return new Response($view);
    }
}
