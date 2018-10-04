<?php

namespace Gini\Controller\CGI\AJAX;

class Login extends \Gini\Controller\CGI
{
    public function actionSendCode()
    {
        $form = $this->form();
        if ('POST' != $_SERVER['REQUEST_METHOD']) {
            $this->redirect('error/401');
        }
        $codes = those('code')->whose('phone')->is($form['phone'])
                ->whose('expire')->isGreaterThan(date('Y-m-d H:i:s'));
        $expire_codes = those('code')->whose('expire')->isLessThan(date('Y-m-d H:i:s'));
        foreach ($expire_codes as $c) { $c->delete(); }
        if ($codes->totalCount()) {
            $this->redirect('error/401');
        }
        $now = time();
        $code = a('code');
        $code->phone = H($form['phone']);
        $code->identity = rand(100000, 999999);
        $code->ctime = date('Y-m-d H:i:s', $now);
        $code->expire = date('Y-m-d H:i:s', $now + 300);
        $code->save();

        // 发送短信码
        $statusStr = array(
            "0" => "短信发送成功",
            "-1" => "参数不全",
            "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
            "30" => "密码错误",
            "40" => "账号不存在",
            "41" => "余额不足",
            "42" => "帐户已过期",
            "43" => "IP地址限制",
            "50" => "内容含有敏感词"
        );

        $smsapi = "http://api.smsbao.com/";
        $user = "jianbing"; // 短信平台帐号
        $pass = md5("23155212"); //短信平台密码
        $content = strtr("【煎饼财富】您正在注册煎饼财富，验证码%code（切勿泄露！），有效期5分钟，任何人向您索要，或要求您打开网页输入验证码均是诈骗行为。如非本人操作，请致电4009618893。", ['%code' => $code->identity]);//要发送的短信内容
        $phone = $code->phone; // 要发送短信的手机号码
        $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
        $result = file_get_contents($sendurl);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\JSON', ['success' => true]);
    }

    public function actionShowServiceProtocol()
    {
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('login/service-protocol', []));
    }
}
