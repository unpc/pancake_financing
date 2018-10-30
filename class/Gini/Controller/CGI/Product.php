<?php

namespace Gini\Controller\CGI;

use \Gini\Model\Help;

class Product extends Layout\Index
{
    public function __index($id=0)
    {
        $form = $this->form();

        $product = a('product', $id);

        $step = 5;

        $news = those('news')->whose('product')->is($product)->orderBy('ctime', 'D');  

        $pagination = \Gini\Model\Help::pagination($news, $form['st'], $step);

        $this->view->body = V('products/profile', [
            'product' => $product,
            'form' => $form,
            'news' => $news
        ]);
    }
}
