<?php

namespace Gini\Controller\CGI\AJAX;

class News extends \Gini\Controller\CGI
{
    public function actionAdd()
    {
        $me = _G('ME');
        $form = $this->form();
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();

            try {

                $validator
                    ->validate('title', $form['title'], T('标题不能为空!'))
                    ->done();

                $n = a('news');
                $n->title = H($form['title']);
                $n->content = $form['content'];
                $n->ctime = date('Y-m-d H:i:s');
                $n->publish = date('Y-m-d H:i:s');
                $n->save();

                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('news/add-news-modal', [
            'form' => $form,
        ]));
    }

    public function actionEdit($id=0)
    {
        $me = _G('ME');
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        $form = $this->form();
        $new = a('news', $id);
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();

            try {

                $validator
                    ->validate('title', $form['title'], T('标题不能为空!'))
                    ->done();

                $new->title = H($form['title']);
                $new->content = $form['content'];
                $new->publish = date('Y-m-d H:i:s');
                $new->save();

                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('news/edit-news-modal', [
            'form' => $form,
            'new' => $new
        ]));
    }

    public function actionDelete($id = 0)
    {
        $me = _G('ME');
        $new = a('news', $id);
        if (!$new->id) {
            $this->redirect('error/404');
        }
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }

        //remove this news
        $new->delete();

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
    }
}
