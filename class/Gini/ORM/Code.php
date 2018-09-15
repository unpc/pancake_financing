<?php

namespace Gini\ORM;

class Code extends Object
{
    public $phone       = 'string:20';
    public $identity    = 'string:10';
    public $expire      = 'datetime';
    public $ctime       = 'datetime';

    protected static $db_index = [
        'phone',
        'identity',
        'expire',
        'ctime',
    ];

    public function save()
    {
        if ($this->ctime == '0000-00-00 00:00:00' || !$this->ctime) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }

}
