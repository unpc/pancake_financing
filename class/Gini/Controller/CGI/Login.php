<?php

namespace Gini\Controller\CGI;

class Login extends Layout\Login
{
    public function __index()
    {
        if (\Gini\Auth::isLoggedIn()) {
            $this->redirect('/');
        }
        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();
            try {
                $validator
                    ->validate('authToken', $form['authToken'], T('手机号码不能为空!'))
                    ->validate('authToken', preg_match('/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/', $form['authToken']), T('请输入有效的手机号!'))
                    ->validate('authPassword', $form['authPassword'], T('登录密码不能为空!'))
                    ->validate('authPassword', preg_match('/(?=^.{8,24}$)(?=(?:.*?\d){1})(?=.*[a-z])(?=(?:.*?[A-Z]){1})(?!.*\s)[0-9a-zA-Z!@#.,$%*()_+^&]*$/', $form['authPassword']), T('请输入8~24位含大写字母、数字密码!'))
                    ->done();

                $auth = \Gini\IoC::construct('\Gini\Auth', H($form['authToken']));
                $password = $form['authPassword'];
                if ($auth->verify($password)) {

                    \Gini\Auth::login(H($form['authToken']));

                    if (isset($_SESSION['#LOGIN_REFERER'])) {
                        $referer = $_SESSION['#LOGIN_REFERER'] ?: null;
                        unset($_SESSION['#LOGIN_REFERER']);
                        if ($referer) {
                            $this->redirect($referer);
                        }
                    }

                    $this->redirect('/');
                }

                $form['_errors']['*'] = T('用户名/密码错误!');
                
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        $this->view->title = '登录';
        $this->view->body = V('login/body', [
            'form' => $form
        ]);
    }

    public function actionRegister()
    {
        if (\Gini\Auth::isLoggedIn()) {
            $this->redirect('/');
        }
        $form = $this->form();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();
            try {
                $code = a('code')->whose('phone')->is($form['authToken'])
                    ->whose('expire')->isGreaterThan(date('Y-m-d H:i:s'));
                $expire_codes = those('code')->whose('expire')->isLessThan(date('Y-m-d H:i:s'));
                foreach ($expire_codes as $c) { $c->delete(); }
                $validator
                    ->validate('authToken', $form['authToken'], T('手机号码不能为空!'))
                    ->validate('authToken', preg_match('/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/', $form['authToken']), T('请输入有效的手机号!'))
                    ->validate('authPassword', $form['authPassword'], T('登录密码不能为空!'))
                    ->validate('authPassword', preg_match('/(?=^.{8,24}$)(?=(?:.*?\d){1})(?=.*[a-z])(?=(?:.*?[A-Z]){1})(?!.*\s)[0-9a-zA-Z!@#.,$%*()_+^&]*$/', $form['authPassword']), T('请输入8~24位含大写字母、数字密码!'))
                    ->validate('authCode', $form['authCode'], T('验证码不能为空!'))
                    ->validate('authCode', $code->identity == $form['authCode'], T('验证码填写错误!'))
                    ->validate('isRead', $form['isRead'] == 'on', T('请确认查看理财服务协议!'))
                    ->done();

                $user = a('user');
                $user->name = $user->username = $user->phone = H($form['authToken']);
                $user->save();
                $auth = \Gini\IoC::construct('\Gini\Auth', $user->username);
                $auth->create(H($form['authPassword']));

                \Gini\Auth::login($user->username);
                $this->redirect('/');
                
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }
        
        $this->view->title = '注册';
        $this->view->body = V('login/register', [
            'form' => $form
        ]);
    }
}
