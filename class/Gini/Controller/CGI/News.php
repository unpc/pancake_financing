<?php

namespace Gini\Controller\CGI;

class News extends Layout\Index
{
    public function __index()
    {
        $form = $this->form();

        $news = those('news');

        $this->view->body = V('news/index', [
            'news' => $news,
            'form' => $form
        ]);
    }

    public function actionProfile($id=0)
    {
        $form = $this->form();

        $new = a('news', $id);

        $this->view->body = V('news/profile', [
            'new' => $new,
            'form' => $form
        ]);
    }
}
