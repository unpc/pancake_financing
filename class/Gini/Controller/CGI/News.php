<?php

namespace Gini\Controller\CGI;

class News extends Layout\Index
{
    public function __index()
    {
        $form = $this->form();

        $news = a('news', $id);

        $this->view->body = V('news/index', [
            'news' => $news,
            'form' => $form
        ]);
    }
}
