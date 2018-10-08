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

        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        $form = $this->form();
        $step = 10;

        $users = those('user');
        $pagination = \Gini\Model\Help::pagination($users, $form['st'], $step);

        
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("admin/users", [
            'form' => $form,
            'active' => 'users',
            'users' => $users,
            'pagination' => $pagination
        ]);
    }

    public function actionProduct()
    {
        $me = _G('ME');
        if (!\Gini\Auth::isLoggedIn() || !$me->id) {
            $this->redirect('/login');
        }
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
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
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
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
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
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
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
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
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        $form = $this->form();
        $agreements = those('agreement');
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("admin/agreement", [
            'form' => $form,
            'active' => 'agreement',
            'agreements' => $agreements
        ]);
    }

    public function actionReserve()
    {
        $me = _G('ME');
        if (!\Gini\Auth::isLoggedIn() || !$me->id) {
            $this->redirect('/login');
        }
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        $form = $this->form();
        $reserves = those('reserve')->orderBy('ctime', 'D');
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("admin/reserve", [
            'form' => $form,
            'active' => 'reserve',
            'reserves' => $reserves
        ]);
    }
}
