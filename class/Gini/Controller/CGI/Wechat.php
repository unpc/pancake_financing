<?php

namespace Gini\Controller\CGI;

class Wechat extends Layout\Wechat
{
    public function __index()
    {
        // 1. 将从微信平台通过get传送过来的参数 timestamp, noce，token按字典排序
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = 'Genee83719730';
        $signature = $_GET['signature'];
        $array = [$timestamp, $nonce, $token];
        sort($array);
        // 2. 将排序后的三个参数拼接后用sha1加密
        $tmpstr = implode('', $array);
        $tmpstr = sha1($tmpstr);
        // 3. 将加密后的字符串与signature进行对比， 判断该请求是否来自微信
        if ($tmpstr == $signature) {
            echo $_GET['echostr'];
            exit;
        }
    }

    public function actionCreateMenu()
    {
        $wechat = \Gini\Config::get('wechat');
        $http = new \Gini\HTTP();

        $r = $http->request('get', "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$wechat['appID']}&secret={$wechat['appsecret']}", []);
        $token = json_decode($r->body, true);
        $access_token = $token['access_token'];

        $params = '{
            "button":[
            {
                "name":"产品中心",
                "type":"view",
                "url":"http://index.labscout.cn/pancake_financing/wechat/product"
             },
             {
                "name":"新闻公告",
                "type":"view",
                "url":"http://index.labscout.cn/pancake_financing/wechat/news"
             },
             {
                "name":"个人中心",
                "type":"view",
                "url":"http://index.labscout.cn/pancake_financing/wechat/mine"
             }
             ]
       }';

        $response = $http->request('post', "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}", $params);
        $this->view = $response;
    }

    public function actionProduct()
    {
        $config = \Gini\Config::get('wechat');
        $wechat = \Gini\IoC::construct('\Wechat\OAuth', $config['appID'], $config['appsecret']);
        $user_info = $wechat->getUserInfo();
        $user = a('user', ['openid' => $user_info['openid']]);
        if ($user->id) {
            \Gini\Auth::login($user->username);
            $this->redirect('/mine/product');
        }
        else {
            $_SESSION['#BIND_REFERER'] = URL('/mine/product');
            $this->redirect('/wechat/bind');
        }
    }

    public function actionMine()
    {
        $config = \Gini\Config::get('wechat');
        $wechat = \Gini\IoC::construct('\Wechat\OAuth', $config['appID'], $config['appsecret']);
        $user_info = $wechat->getUserInfo();
        $user = a('user', ['openid' => $user_info['openid']]);
        if ($user->id) {
            \Gini\Auth::login($user->username);
            $this->redirect('/mine');
        }
        else {
            $_SESSION['#BIND_REFERER'] = URL('/mine');
            $this->redirect('/wechat/bind');
        }
    }

    public function actionNews()
    {
        $config = \Gini\Config::get('wechat');
        $wechat = \Gini\IoC::construct('\Wechat\OAuth', $config['appID'], $config['appsecret']);
        $user_info = $wechat->getUserInfo();
        $user = a('user', ['openid' => $user_info['openid']]);
        if ($user->id) {
            \Gini\Auth::login($user->username);
            $this->redirect('/news');
        }
        else {
            $_SESSION['#BIND_REFERER'] = URL('/news');
            $this->redirect('/wechat/bind');
        }
    }

    public function actionBind()
    {
        $config = \Gini\Config::get('wechat');
        $wechat = \Gini\IoC::construct('\Wechat\OAuth', $config['appID'], $config['appsecret']);
        $user_info = $wechat->getUserInfo();
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

                    $user = a('user', ['username' => $form['authToken']]);
                    if ($user->id) {
                        $user->openid = $user_info['openid'];
                        $user->save();

                        \Gini\Auth::login($user->username);

                        if (isset($_SESSION['#BIND_REFERER'])) {
                            $referer = $_SESSION['#BIND_REFERER'] ?: null;
                            unset($_SESSION['#BIND_REFERER']);
                            if ($referer) {
                                $this->redirect($referer);
                            }
                        }
    
                        $this->redirect('/');
                    }
                }

                $form['_errors']['*'] = T('用户名/密码错误!');
                
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        $this->view->title = '微信用户绑定';
        $this->view->body = V('login/bind-wechat', [
            'form' => $form
        ]);
    }
}
