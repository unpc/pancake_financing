<?php

namespace Gini\ORM;

class Reserve extends Object
{
    public $user        = 'object:user';
    public $name        = 'string:20';
    public $phone       = 'string:50';
    public $product     = 'object:product';
    public $time        = 'datetime';
    public $time_m      = 'string:10';
    public $ctime       = 'datetime';

    protected static $db_index = [
        'user',
        'product',
        'name',
        'phone',
        'time'
    ];

    public function save()
    {
        if ($this->ctime == '0000-00-00 00:00:00' || !$this->ctime) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }

}
