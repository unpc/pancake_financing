<?php

namespace Gini\Controller\CGI;

class Wechat extends Layout
{
    public function __index()
    {
        $get = $_GET;
        if (! (isset($get['signature']) && isset($get['timestamp']) && isset($get['nonce']))) {
            return false;
        }
        $token = 'Genee83719730';
        $signature = $get['signature'];
        $timestamp = $get['timestamp'];
        $nonce = $get['nonce'];

        $signatureArray = array($token, $timestamp, $nonce);
        sort($signatureArray, SORT_STRING);
        $return = sha1(implode($signatureArray)) == $signature ? $get['echostr'] : false;

        echo (string)$return;
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
}
