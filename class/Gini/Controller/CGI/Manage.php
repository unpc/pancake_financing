<?php

namespace Gini\Controller\CGI;

class Manage extends Layout\Index
{
    public function __index()
    {
        $me = _G('ME');
        if (!\Gini\Auth::isLoggedIn() || !$me->id) {
            $this->redirect('/login');
        }

        $users = those('user');

        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("admin/users", [
            'form' => $form,
            'active' => 'users',
            'users' => $users
        ]);
    }

    public function actionProduct()
    {
        $me = _G('ME');
        if (!\Gini\Auth::isLoggedIn() || !$me->id) {
            $this->redirect('/login');
        }
        $products = those('product');

        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("admin/products", [
            'form' => $form,
            'active' => 'product',
            'products' => $products
        ]);
    }
}
