<?php

namespace Gini\Controller\CGI;

class Mine extends Layout\Index
{
    public function __index($type='info')
    {
        $me = _G('ME');
        if (!\Gini\Auth::isLoggedIn() || !$me->id) {
            $this->redirect('/login');
        }
        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("mine/{$type}", [
            'form' => $form,
            'type' => $type
        ]);
    }
}
