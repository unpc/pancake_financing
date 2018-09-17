<?php

namespace Gini\Controller\CGI;

use \Gini\Model\Help;

class Product extends Layout\Index
{
    public function __index($id=0)
    {
        $form = $this->form();

        $product = a('product', $id);

        $this->view->body = V('products/profile', [
            'product' => $product,
            'form' => $form
        ]);
    }
}
