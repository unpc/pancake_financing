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
        $http = new \Gini\HTTP();
        $r = $http->request('get', 'https://api.smsbao.com/sms', [
            'u' => 'jianbing',
            'p' => md5('23155212'),
            'm' => H($form['phone']),
            'c' => urlencode(strtr("【煎饼财富】您正在注册煎饼财富，验证码%code（切勿泄露！），有效期5分钟，任何人向您索要，或要求您打开网页输入验证码均是诈骗行为。如非本人操作，请致电4009618893。", ['%code' => $code->identity]))
        ]);
        
        return \Gini\IoC::construct('\Gini\CGI\Response\JSON', ['success' => true]);
    }

    public function actionShowServiceProtocol()
    {
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('login/service-protocol', []));
    }
}
