<?php

namespace Gini\Controller\CGI;

class About extends Layout\Index
{
    public function __index()
    {
        $me = _G('ME');
        $form = $this->form();
        $this->view->body = V('about', [
            'form' => $form,
        ]);
    }
}
