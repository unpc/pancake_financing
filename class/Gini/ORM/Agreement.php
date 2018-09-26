<?php

namespace Gini\ORM;

class Agreement extends Object
{
    public $user        = 'object:user';
    public $idCard      = 'string:20';
    public $product     = 'object:product';
    public $amount      = 'int';
    public $ctime       = 'datetime';

    protected static $db_index = [
        'user',
        'product',
        'amount',
        'ctime',
    ];

    public function save()
    {
        if ($this->ctime == '0000-00-00 00:00:00' || !$this->ctime) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }

}
