<?php

namespace Gini\Controller\CGI\AJAX;

class User extends \Gini\Controller\CGI
{
    public function actionAdd()
    {
        $me = _G('ME');
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();

            try {
                $username = H($form['username']);

                $exist_user = a('user')
                            ->whose('username')
                            ->is($username);

                $validator
                    ->validate('username', $form['username'], T('用户账号不能为空!'))
                    ->validate('username', preg_match('/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/', $form['username']), T('请输入有效的手机号!'))
                    ->validate('username', !$exist_user->id, T('请更换其他手机账号!'))
                    ->validate('password', $form['password'], T('登录密码不能为空!'))
                    ->validate('password', preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,24}$/', $form['password']), T('请输入8~24位含字母、数字密码!'))
                    ->validate('name', $form['name'], T('姓名不能为空!'));

                if ($form['idcard']) {
                    $validator->validate('idcard', preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $form['idcard']), T('请填写合法的身份证信息!'));
                }

                $validator->done();

                $user = a('user');
                $user->name = H($form['name']);
                $user->username = $username;
                $user->email = H($form['email']);
                $user->type = (int)$form['type'];
                $user->group_name = H($form['group_name']);
                $user->idcard = H($form['idcard']);
                if ($me->isAllowedTo('超级管理')) {
                    $user->is_admin = H($form['is_admin']) == 'on' ? 1 : 0;
                }
                if ($me->isAllowedTo('管理')) {
                    $user->is_runner = H($form['is_runner']) == 'on' ? 1 : 0;
                }
                $user->save();

                $auth = \Gini\IoC::construct('\Gini\Auth', $username);
                $auth->create(H($form['password'] ?: 'Az123456'));

                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('users/add-user-modal', [
            'form' => $form,
        ]));
    }

    public function actionEdit($id = 0)
    {
        $me = _G('ME');
        $user = a('user', $id);
        if (!$user->id) {
            $this->redirect('error/404');
        }
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        $form = $this->form();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();

            try {
                $username = H($form['username']);
                $exist_user = a('user')
                            ->whose('username')
                            ->is($username)
                            ->andWhose('id')->isNot((int)$user->id);
                $validator
                    ->validate('username', $form['username'], T('用户账号不能为空!'))
                    ->validate('username', preg_match('/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/', $form['username']), T('请输入有效的手机号!'))
                    ->validate('username', !$exist_user->id, T('请更换其他手机账号!'))
                    ->validate('name', $form['name'], T('姓名不能为空!'));
                if ($form['password']) {
                    $validator->validate('password', preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,24}$/', $form['password']), T('请输入8~24位含字母、数字密码!'));
                }
                if ($form['idcard']) {
                    $validator->validate('idcard', preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $form['idcard']), T('请填写合法的身份证信息!'));
                }
                $validator->done();

                $user->name = H($form['name']);
                $user->username = $username;
                $user->email = H($form['email']);
                $user->type = (int)$form['type'];
                $user->group_name = H($form['group_name']);
                $user->idcard = H($form['idcard']);
                if ($me->isAllowedTo('超级管理')) {
                    $user->is_admin = H($form['is_admin']) == 'on' ? 1 : 0;
                }
                if ($me->isAllowedTo('管理')) {
                    $user->is_runner = H($form['is_runner']) == 'on' ? 1 : 0;
                }
                $user->save();

                if ($form['password']) {
                    $auth = \Gini\IoC::construct('\Gini\Auth', $username);
                    $auth->changePassword(H($form['password'] ?: 'Az123456'));
                }
                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('users/edit-user-modal', [
            'form' => $form,
            'user' => $user,
        ]));
    }

    public function actionDelete($id = 0)
    {
        $me = _G('ME');
        $user = a('user', $id);
        if (!$user->id) {
            $this->redirect('error/404');
        }
        if (!$me->isAllowedTo('超级管理')) {
            $this->redirect('error/401');
        }
        //remove this user
        $user->delete();

        $auth = \Gini\IoC::construct('\Gini\Auth', $user->username);
        $auth->remove();

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
    }

    public function actionGetUsers()
    {
        $me = _G('ME');
        $form = $this->form();
        $objects = [];

        try {
            $users = those('user')
                    ->whose('name')->contains(H($form['query']))
                    ->orWhose('username')->contains(H($form['query']));
            foreach ($users as $key => $user) {
                $objects[$key] = [
                    'name' => $user->name,
                    'id' => $user->id,
                    'username' => $user->username
                ];
            }
        } catch (\Gini\RPC\Exception $e) {
            $objects = [];
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\JSON', $objects);
    }
}
