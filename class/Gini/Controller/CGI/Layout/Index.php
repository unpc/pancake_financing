<?php

namespace Gini\Controller\CGI\Layout;

abstract class Index extends \Gini\Controller\CGI\Layout
{
    protected static $layout_name = 'layout/index';
    
    public function __postAction($action, &$params, $response)
    {

        $route = \Gini\CGI::route();
        if ($route) {
            $args = explode('/', $route);
        }
        if (!$route || count($args) == 0) {
            $args = ['index'];
        }

        $this->view->title = '煎饼理财';
        $this->view->header = V('header', ['selected' => $args[0]]);
        $this->view->footer = V('footer');

        return parent::__postAction($action, $params, $response);
    }
}
