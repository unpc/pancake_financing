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
                $validator
                    ->validate('title', $form['title'], T('产品名称不能为空!'))
                    ->done();

                $product = a('product');
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
                $validator
                    ->validate('title', $form['title'], T('产品名称不能为空!'))
                    ->done();

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
}
