<?php

namespace Gini\Controller\CGI\AJAX;

class User extends \Gini\Controller\CGI
{
    public function actionAdd()
    {
        $me = _G('ME');
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
                    ->validate('password', preg_match('/(?=^.{8,24}$)(?=(?:.*?\d){1})(?=.*[a-z])(?=(?:.*?[A-Z]){1})(?!.*\s)[0-9a-zA-Z!@#.,$%*()_+^&]*$/', $form['password']), T('请输入8~24位含大写字母、数字密码!'))
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
                    $validator->validate('password', preg_match('/(?=^.{8,24}$)(?=(?:.*?\d){1})(?=.*[a-z])(?=(?:.*?[A-Z]){1})(?!.*\s)[0-9a-zA-Z!@#.,$%*()_+^&]*$/', $form['password']), T('请输入8~24位含大写字母、数字密码!'));
                }
                $validator->done();

                $new_user = a('user');
                $new_user->name = H($form['name']);
                $new_user->username = $username;
                $new_user->email = H($form['email']);
                $new_user->type = (int)$form['type'];
                $new_user->group_name = H($form['group_name']);
                $new_user->is_admin = H($form['is_admin']) == 'on' ? 1 : 0;
                $new_user->is_runner = H($form['is_runner']) == 'on' ? 1 : 0;
                $new_user->save();

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
        if (!$me->isAllowedTo('删除用户', $user)) {
            return;
        }

        //remove this user
        $user->delete();

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
    }
}