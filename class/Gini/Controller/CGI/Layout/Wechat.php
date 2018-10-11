<?php

namespace Gini\Controller\CGI\Layout;

abstract class Wechat extends \Gini\Controller\CGI\Layout
{
    protected static $layout_name = 'layout/wechat';

    public function __postAction($action, &$params, $response)
    {   
        return parent::__postAction($action, $params, $response);
    }
}
