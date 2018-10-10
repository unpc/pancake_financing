<?php

namespace Gini\Controller\CGI;

use \Gini\Model\Help;

class Products extends Layout\Index
{
    public function __index($type = 1)
    {
        $form = $this->form();
        $step = 10;
        $products = those('product')->whose('publish')->is(\Gini\ORM\Product::PUBLISH_YET);

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            //获取post参数 并校验
            $form = $this->form('post');
            if ($form['title']) {
                $products = $products
                        ->whose('title')->contains(H($form['title']));
            }

            if ($form['ref_no']) {
                $products = $products
                    ->whose('number')->contains(H($form['ref_no']));
            }

            if ($form['open_start'] && $form['open_end'] && strtotime($form['open_end']) < strtotime($form['open_start'])) {
                list($form['open_start'], $form['open_end']) = [$form['open_end'], $form['open_start']];
            }
            if ($form['open_start']) {
                $products = $products
                    ->whose('open_day')->isGreaterThanOrEqual(H($form['open_start']));
            }
            if ($form['open_end']) {
                $products = $products
                    ->whose('open_day')->isLessThanOrEqual(H($form['open_end']));
            }

            if ($form['dead_start'] && $form['dead_end'] && $form['dead_end'] < $form['dead_start']) {
                list($form['dead_start'], $form['dead_end']) = [$form['dead_end'], $form['dead_start']];
            } 

            if ($form['dead_start']) {
                $products = $products
                    ->whose('dead_day')->isGreaterThanOrEqual(H($form['dead_start']));
            }
            if ($form['dead_end']) {
                $products = $products
                    ->whose('dead_day')->isLessThanOrEqual(H($form['dead_end']));
            }

            if ($form['purchase_start'] && $form['purchase_end'] && $form['purchase_end'] < $form['purchase_start']) {
                list($form['purchase_start'], $form['purchase_end']) = [$form['purchase_end'], $form['purchase_start']];
            }
            if ($form['purchase_start']) {
                $products = $products
                    ->whose('purchase')->isGreaterThanOrEqual(H($form['purchase_start']));
            }
            if ($form['purchase_end']) {
                $products = $products
                    ->whose('purchase')->isLessThanOrEqual(H($form['purchase_end']));
            }
        }

        $pagination = Help::pagination($products, $form['st'], $step);
        $this->view->body = V('products/index', [
            'products' => $products,
            'form' => $form,
            'pagination' => $pagination
        ]);
    }
}
