<?php

namespace Gini\Controller\CGI\AJAX;

class Product extends \Gini\Controller\CGI
{
    public function actionAdd()
    {
        $me = _G('ME');
        $form = $this->form();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $validator = new \Gini\CGI\Validator;

            try {
                $exist_product = a('product', ['number' => H($form['number'])]);

                $validator
                    ->validate('title', $form['title'], T('产品名称不能为空!'))
                    ->validate('number', H($form['number']), T('产品编号不能为空!'))
                    ->validate('number', !$exist_product->id, T('产品编号不能重复!'))
                    ->validate('amount', $form['amount'], T('购买总额不能为空!'))
                    ->validate('amount', is_numeric($form['amount']), T('购买总额必须为数字!'))
                    ->done();

                $product = a('product');
                $product->number = H($form['number']);
                $product->title = H($form['title']);
                $product->Issuer = H($form['Issuer']);
                $product->create_day = H($form['create_day']);
                $product->dead_day = (int)$form['dead_day'];
                $product->open_day = H($form['open_day']);
                $product->open_day_rule = H($form['open_day_rule']);
                $product->purchase = H($form['purchase']);
                $product->purchase_object = H($form['purchase_object']);
                $product->admin_rate = H($form['admin_rate']);
                $product->distribution = H($form['distribution']);
                $product->redemption_rate = H($form['redemption_rate']);
                $product->amount = (int)$form['amount'];
                $product->save();


                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('products/add-product-modal', [
            'form' => $form
        ]));
    }

    public function actionEdit($id=0)
    {
        $me = _G('ME');
        $form = $this->form();
        $product = a('product', $id);
        if (!$product->id) {
            $this->redirect('error/401');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $validator = new \Gini\CGI\Validator;

            try {
                $exist_product = a('product')
                        ->whose('number')->is(H($form['number']))
                        ->andWhose('id')->isNot($product->id);

                $validator
                    ->validate('title', $form['title'], T('产品名称不能为空!'))
                    ->validate('number', H($form['number']), T('产品编号不能为空!'))
                    ->validate('number', !$exist_product->id, T('产品编号不能重复!'))
                    ->validate('amount', $form['amount'], T('购买总额不能为空!'))
                    ->validate('amount', is_numeric($form['amount']), T('购买总额必须为数字!'))
                    ->done();

                $product->number = H($form['number']);
                $product->title = H($form['title']);
                $product->Issuer = H($form['Issuer']);
                $product->create_day = H($form['create_day']);
                $product->dead_day = (int)$form['dead_day'];
                $product->open_day = H($form['open_day']);
                $product->open_day_rule = H($form['open_day_rule']);
                $product->purchase = H($form['purchase']);
                $product->purchase_object = H($form['purchase_object']);
                $product->admin_rate = H($form['admin_rate']);
                $product->distribution = H($form['distribution']);
                $product->redemption_rate = H($form['redemption_rate']);
                $product->amount = (int)$form['amount'];
                $product->save();


                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('products/edit-product-modal', [
            'form' => $form,
            'product' => $product
        ]));
    }

    public function actionDelete($id=0)
    {
        $me = _G('ME');
        $product = a('product', $id);
        if (!$product->id) {
            $this->redirect('error/404');
        }
        //remove this project
        $product->delete();

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
    }

    public function actionGetProducts()
    {
        $me = _G('ME');
        $form = $this->form();
        $objects = [];

        try {
            $products = those('product')
                    ->whose('title')->contains(H($form['query']));
            foreach ($products as $key => $product) {
                $objects[$key] = [
                    'name' => $product->title,
                    'id' => $product->id
                ];
            }
        } catch (\Gini\RPC\Exception $e) {
            $objects = [];
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\JSON', $objects);
    }
}
