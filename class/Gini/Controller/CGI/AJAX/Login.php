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
        if ($codes->totalCount()) {
            $this->redirect('error/401');
        }
        $now = time();
        $code = a('code');
        $code->phone = H($form['phone']);
        $code->identity = rand(100000, 999999);
        $code->ctime = date('Y-m-d H:i:s', $now);
        $code->expire = date('Y-m-d H:i:s', $now + 120);
        $code->save();

        // 发送短信码
        
        return \Gini\IoC::construct('\Gini\CGI\Response\JSON', ['success' => true]);
    }

    public function actionShowServiceProtocol()
    {
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('login/service-protocol', []));
    }
}
