<?php
namespace App\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Monolog\Logger;

final class PageAction
{
    private $view;
    private $logger;
    private $router;
    private $flash;

    public function __construct(Twig $view, Logger $logger, Router $router, Messages $flash)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->router = $router;
        $this->flash = $flash;
    }

    /** General dispatcher. looks up controller and then displays it */
    public function dispatch(Request $request, Response $response, Array $args)
    {
        $this->logger->info("Page lookup action dispatched");
        $page_not_found = true;
        $controller = strtolower($args['controller']);
        if(key_exists('subpage',$args)){
            $subpage = strtolower($args['subpage']);
        }



        if($page_not_found){
            $this->flash->addMessage('flash','Page Not Found');
            return $response->withRedirect($this->router->pathFor('homepage'));
        } else {
            $this->view->render($response, 'page.twig');
            return $response;
        }

    }
}
