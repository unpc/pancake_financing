<?php

namespace Gini\ORM;

class Product extends Object
{
    public $title = 'string:100';
    public $rate = 'string:10';
    public $rate_desc = 'string:50';
    public $dead_day = 'int';
    public $open_day = 'datetime';
    // 购买金额
    public $purchase = 'int';
    public $purchase_object = 'string:100';
    public $admin_rate = 'string:10';

    public $distribution = 'string:100';
    public $status = 'int,default:0';
    public $ctime = 'datetime';

    public $amount = 'int';
    public $number = 'string:50';

    public $publish = 'int';

    protected static $db_index = [
        'title',
        'rate',
        'purchase',
        'dead_day',
        'open_day',
        'admin_rate',
        'status',
        'ctime',
        'amount',
        'publish',
        'unique:number'
    ];

    const TYPE_GDSY = 1;
    const TYPE_HHX = 2;
    const TYPE_DCJJ = 3;

    public static $TYPE = [
        self::TYPE_GDSY => '固定收益',
        self::TYPE_HHX => '混合型',
        self::TYPE_DCJJ => '对冲基金',
    ];

    const STATUS_XSZ  = 1;
    const STATUS_YS = 2;
    const STATUS_DS = 3;
    const STATUS_SQ = 4;
    const STATUS_FBQ = 5;
    const STATUS_YZZ = 6;

    public static $STATUS = [
        self::STATUS_XSZ => '销售中',
        self::STATUS_YS => '预售',
        self::STATUS_DS => '待售',
        self::STATUS_SQ => '售罄',
        self::STATUS_FBQ => '封闭期',
        self::STATUS_YZZ => '已终止'
    ];

    const PUBLISH_NOTHING = 0;
    const PUBLISH_YET = 1;
    const PUBLISH_EXPIRE = 2;

    public static $PUBLISH = [
        self::PUBLISH_NOTHING => '未发布',
        self::PUBLISH_YET => '已发布',
        self::PUBLISH_EXPIRE => '已过期'
    ];

    public function save()
    {
        if ('0000-00-00 00:00:00' == $this->ctime || !$this->ctime) {
            $this->ctime = date('Y-m-d H:i:s');
        }

        return parent::save();
    }
}
