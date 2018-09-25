<?php

namespace Gini\Controller\CGI\AJAX;

class News extends \Gini\Controller\CGI
{
    public function actionAdd()
    {
        $me = _G('ME');
        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();

            try {

                $validator
                    ->validate('name', $form['name'], T('姓名不能为空!'))
                    ->done();

                $user = a('user');
                $user->name = H($form['name']);
                $user->username = $username;
                $user->email = H($form['email']);
                $user->type = (int)$form['type'];
                $user->group_name = H($form['group_name']);
                $user->is_admin = H($form['is_admin']) == 'on' ? 1 : 0;
                $user->is_runner = H($form['is_runner']) == 'on' ? 1 : 0;
                $user->save();

                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('news/add-news-modal', [
            'form' => $form,
        ]));
    }
}
