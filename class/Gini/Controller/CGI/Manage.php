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

        if ($form['username']) {
            $users = $users->whose('username')->contains(H($form['username']));
        }

        if ($form['idcard']) {
            $users = $users->whose('idcard')->contains(H($form['idcard']));
        }

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

    public function actionProduct($publish=0)
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
        $products = those('product')->whose('publish')->is((int)$publish);

        if ($form['number']) {
            $products = $products->whose('number')->contains(H($form['number']));
        }

        $products = $products->orderBy('ctime', 'D');

        $pagination = \Gini\Model\Help::pagination($products, $form['st'], $step);

        $this->view->body = V("admin/products", [
            'form' => $form,
            'active' => 'product',
            'products' => $products,
            'publish' => $publish,
            'pagination' => $pagination
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
        
        $form = $this->form();
        $step = 10;
        $news = those('news');

        $pagination = \Gini\Model\Help::pagination($news, $form['st'], $step);

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        
        $this->view->body = V("admin/news", [
            'form' => $form,
            'active' => 'news',
            'news' => $news,
            'pagination' => $pagination
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
        $step = 10;
        $agreements = those('agreement');

        if ($form['phone']) {
            $agreements = $agreements->whose('user')->isIn(
                those('user')->whose('username')->contains(H($form['phone']))
            );
        }

        if ($form['number']) {
            $agreements = $agreements->whose('product')->isIn(
                those('product')->whose('number')->contains(H($form['number']))
            );
        }

        $pagination = \Gini\Model\Help::pagination($agreements, $form['st'], $step);

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("admin/agreement", [
            'form' => $form,
            'active' => 'agreement',
            'agreements' => $agreements,
            'pagination' => $pagination
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

        $reserves = those('reserve');
        if ($form['phone']) {
            $reserves = $reserves->whose('user')->isIn(
                those('user')->whose('username')->contains(H($form['phone']))
            );
        }

        if ($form['number']) {
            $reserves = $reserves->whose('product')->isIn(
                those('product')->whose('number')->contains(H($form['number']))
            );
        }

        $step = 10;
        $reserves = $reserves->orderBy('ctime', 'D');
        $pagination = \Gini\Model\Help::pagination($reserves, $form['st'], $step);
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
        }
        $this->view->body = V("admin/reserve", [
            'form' => $form,
            'active' => 'reserve',
            'reserves' => $reserves,
            'pagination' => $pagination
        ]);
    }
}
