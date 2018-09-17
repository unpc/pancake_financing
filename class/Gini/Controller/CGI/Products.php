<?php

namespace Gini\Controller\CGI;

use \Gini\Model\Help;

class Products extends Layout\Index
{
    public function __index($type = 1)
    {
        $form = $this->form();
        $get_form = (array)$this->form('get');
        $step = 10;
        $products = those('product');

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            //获取post参数 并校验
            $form = $this->form('post');
            if ($form['title']) {
                $products = $products->whose('title')->contains(H($form['title']));
            }
        }

        if (count($get_form)) {
            if ((int)$form['type']) {
                $products = $products->whose('type')->is((int)$form['type']);
            }
            if ((int)$form['status']) {
                $products = $products->whose('status')->is((int)$form['status']);
            }
        }

        $pagination = Help::pagination($products, $form['st'], $step);
        $this->view->body = V('products/index', [
            'products' => $products,
            'form' => $form,
            'get_form' => $get_form,
            'pagination' => $pagination
        ]);
    }
}
