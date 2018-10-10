<?php

namespace Gini\Controller\CGI;

class News extends Layout\Index
{
    public function __index()
    {
        $form = $this->form();
        
        $step = 10;

        $news = those('news');

        $pagination = \Gini\Model\Help::pagination($news, $form['st'], $step);

        $this->view->body = V('news/index', [
            'news' => $news,
            'form' => $form,
            'pagination' => $pagination
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
