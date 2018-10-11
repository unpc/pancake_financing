<?php

namespace Gini\Controller\CGI;

class Wechat extends Layout
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
                "url":"http://39.104.117.197/wechat/product"
             },
             {
                "name":"新闻公告",
                "type":"view",
                "url":"http://39.104.117.197/wechat/news"
             },
             {
                "name":"个人中心",
                "type":"view",
                "url":"http://39.104.117.197/wechat/mine"
             }
             ]
       }';

        $response = $http->request('post', "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}", $params);
        $this->view = $response;
    }

    public function actionProduct()
    {

    }

    public function actionMine()
    {
        
    }

    public function actionNews()
    {
        
    }

    private function getWechatUser()
    {

    }
}
