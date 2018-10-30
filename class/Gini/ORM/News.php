<?php

namespace Gini\ORM;

class News extends Object
{
    public $title = 'string:200';
    public $content = 'string:*';
    public $type = 'int:1';
    public $ctime = 'datetime';
    public $publish = 'datetime';
    
    public $product = 'object:product';

    protected static $db_index = [
        'title',
        'publish',
        'ctime',
        'type',
        'product'
    ];

    const TYPE_NORMAL = 1;
    const TYPE_PRODUCT = 2;

    public static $TYPE = [
        self::TYPE_NORMAL => '新闻公告',
        self::TYPE_PRODUCT => '产品公告'
    ];

    public function save()
    {
        if ('0000-00-00 00:00:00' == $this->ctime || !$this->ctime) {
            $this->ctime = date('Y-m-d H:i:s');
        }
        return parent::save();
    }
}
