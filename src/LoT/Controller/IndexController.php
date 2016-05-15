<?php
namespace LoT\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class IndexController
{
    public function indexAction(Request $request)
    {
        return new Response('HOMEPAGE: ' . print_r($request->getQueryString(), true));
    }
}
