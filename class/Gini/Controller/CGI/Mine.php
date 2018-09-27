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
            $validator = new \Gini\CGI\Validator();
            try {
                if ($form['submit-info']) {
                    $validator
                        ->validate('name', $form['name'], T('姓名不能为空!'))
                        ->done();
                    $me->name = H($form['name']);
                    $me->email = H($form['email']);
                    $me->group_name = H($form['group']);
                    $me->save();
                }
                if ($form['submit-password']) {
                    $auth = \Gini\IoC::construct('\Gini\Auth', $me->username);
                    $validator
                        ->validate('pwd', $form['pwd'], T('登录密码不能为空!'))
                        ->validate('pwd', preg_match('/(?=^.{8,24}$)(?=(?:.*?\d){1})(?=.*[a-z])(?=(?:.*?[A-Z]){1})(?!.*\s)[0-9a-zA-Z!@#.,$%*()_+^&]*$/', $form['pwd']), T('请输入8~24位含大写字母、数字密码!'))
                        ->validate('code', $form['code'], T('新密码不能为空!'))
                        ->validate('code', preg_match('/(?=^.{8,24}$)(?=(?:.*?\d){1})(?=.*[a-z])(?=(?:.*?[A-Z]){1})(?!.*\s)[0-9a-zA-Z!@#.,$%*()_+^&]*$/', $form['code']), T('请输入8~24位含大写字母、数字密码!'))
                        ->validate('re_code', $form['re_code'], T('重复新密码不能为空!'))
                        ->validate('re_code', $form['re_code'] == $form['code'], T('两次输入密码必须一致!'))
                        ->validate('pwd', $auth->verify(H($form['pwd'])), T('密码输入错误!'))
                        ->done();
                    
                    $auth->changePassword(H($form['code']));
                    $form = [];
                }
                
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }
        $this->view->body = V("mine/{$type}", [
            'form' => $form,
            'type' => $type
        ]);
    }
}
