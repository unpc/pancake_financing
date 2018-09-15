<?php

namespace Gini\Controller\CGI\Layout;

abstract class Login extends \Gini\Controller\CGI\Layout
{
    protected static $layout_name = 'layout/login';

    public function __postAction($action, &$params, $response)
    {
        $route = \Gini\CGI::route();

        if ($route) {
            $args = explode('/', $route);
        }
        if (!$route || count($args) == 0) {
            $args = ['index'];
        }

        $args = array_slice($args, 0, 2);

        $this->view->header = V('login/header');
        $this->view->footer = V('login/footer');
        
        return parent::__postAction($action, $params, $response);
    }
}
