<?php

namespace Gini\Controller\CGI;

class Index extends Layout\Index
{
    public function __index()
    {
        $this->view->body = V('index');
    }

    public function actionLogOut()
    {
        \Gini\Auth::logout();
        $this->redirect('/');
    }
}
