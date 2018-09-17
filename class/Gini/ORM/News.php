<?php

namespace Gini\ORM;

class News extends Object
{
    public $title = 'string:200';
    public $content = 'string:*';
    public $ctime = 'datetime';
    public $publish = 'datetime';

    protected static $db_index = [
        'title',
        'publish',
        'ctime',
    ];

    public function save()
    {
        if ('0000-00-00 00:00:00' == $this->ctime || !$this->ctime) {
            $this->ctime = date('Y-m-d H:i:s');
        }
        return parent::save();
    }
}
