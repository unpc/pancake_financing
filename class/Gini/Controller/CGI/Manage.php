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

    public function actionNews()
    {
        $me = _G('ME');
        if (!\Gini\Auth::isLoggedIn() || !$me->id) {
            $this->redirect('/login');
        }
        $news = those('news');
        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("admin/news", [
            'form' => $form,
            'active' => 'news',
            'news' => $news
        ]);
    }

    public function actionAbout()
    {
        $me = _G('ME');
        if (!\Gini\Auth::isLoggedIn() || !$me->id) {
            $this->redirect('/login');
        }
        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            Hub('about_desc_info', $form['desc_info']);
            Hub('about_desc_scope', $form['desc_scope']);
        }
        $this->view->body = V("admin/about", [
            'form' => $form,
            'active' => 'about'
        ]);
    }

    public function actionBanner()
    {
        $me = _G('ME');
        if (!\Gini\Auth::isLoggedIn() || !$me->id) {
            $this->redirect('/login');
        }
        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("admin/banner", [
            'form' => $form,
            'active' => 'banner'
        ]);
    }

    public function actionAgreement()
    {
        $me = _G('ME');
        if (!\Gini\Auth::isLoggedIn() || !$me->id) {
            $this->redirect('/login');
        }
        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("admin/agreement", [
            'form' => $form,
            'active' => 'agreement'
        ]);
    }
}
