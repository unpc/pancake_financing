<?php

namespace Gini\Controller\CGI\AJAX;

class Agreement extends \Gini\Controller\CGI
{
    public function actionAdd()
    {
        $me = _G('ME');
        $form = $this->form();
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();

            try {

                $product = a('product', (int)$form['product_id']);

                $validator
                    ->validate('user_name', $form['user_name'], T('姓名不能为空!'))
                    ->validate('user_name', $form['user_id'], T('请选择人员信息!'))
                    ->validate('product', $form['product'], T('产品不能为空!'))
                    ->validate('product', $form['product_id'], T('请选择产品信息!'))
                    ->validate('amount', $form['amount'], T('购买金额不能为空!'))
                    ->validate('amount', $form['amount'] <= $product->balance * 10000, T("购买金额不能大于{$product->balance}!"))
                    ->validate('amount', is_numeric($form['amount']), T('购买金额必须为数字!'))
                    ->validate('idcard', preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $form['idcard']), T('请填写合法的身份证信息!'))
                    ->done();
                $agreement = a('agreement');
                $agreement->user = a('user', (int)$form['user_id']);
                $agreement->product = $product;
                $agreement->idCard = H($form['idcard']);
                $agreement->amount = (int)$form['amount'];
                if ($agreement->save()) {
                    $product->balance = $product->balance - ($agreement->amount / 10000);
                    $product->save();
                }

                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('agreements/add-agreement-modal', [
            'form' => $form,
        ]));
    }

    public function actionEdit($id = 0)
    {
        $me = _G('ME');
        $form = $this->form();
        $agreement = a('agreement', $id);
        if (!$agreement->id) {
            $this->redirect('error/404');
        }
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();

            try {
                $validator
                    ->validate('user_name', $form['user_name'], T('姓名不能为空!'))
                    ->validate('user_name', $form['user_id'], T('请选择人员信息!'))
                    ->validate('product', $form['product'], T('产品不能为空!'))
                    ->validate('product', $form['product_id'], T('请选择产品信息!'))
                    ->validate('amount', $form['amount'], T('购买金额不能为空!'))
                    ->validate('amount', is_numeric($form['amount']), T('购买金额必须为数字!'))
                    ->validate('idcard', preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $form['idcard']), T('请填写合法的身份证信息!'))
                    ->done();
                $agreement->user = a('user', (int)$form['user_id']);
                $agreement->product = a('product', (int)$form['product_id']);
                $agreement->idCard = H($form['idcard']);
                $amount = $agreement->amount;
                $agreement->amount = (int)$form['amount'];
                $agreement->save();
                if ($agreement->save()) {
                    $product = $agreement->product;
                    $product->balance = $product->balance + ($amount - $agreement->amount) / 10000;
                    $product->save();
                }

                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('agreements/edit-agreement-modal', [
            'form' => $form,
            'agreement' => $agreement
        ]));
    }

    public function actionDelete($id = 0)
    {
        $me = _G('ME');
        $agreement = a('agreement', $id);
        if (!$agreement->id) {
            $this->redirect('error/404');
        }
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        
        //remove this user
        $amount = $agreement->amount / 10000;
        $product = $agreement->product;
        $agreement->delete();

        $product->balance = $product->balance + $amount;
        $product->save();

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
    }
}
