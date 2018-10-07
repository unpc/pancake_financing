<?php

namespace Gini\ORM;

class User extends Object
{
    public $name        = 'string:50';
    public $name_abbr   = 'string:100';
    public $gender      = 'bool';
    public $username    = 'string:120';
    public $admin       = 'bool';
    public $email       = 'string:120';
    public $phone       = 'string:120';
    public $ctime       = 'datetime';
    public $group       = 'array';
    public $type        = 'int';
    public $is_admin    = 'int,default:0';
    public $is_runner   = 'int,default:0';

    protected static $db_index = array(
        'unique:username',
        'name_abbr',
        'ctime',
        );
    
    CONST TYPE_USER = 0;
    CONST TYPE_WORKER = 1;

    public static $TYPE = [
        self::TYPE_USER => '客户',
        self::TYPE_WORKER => '职工'
    ];

    public function isAllowedTo($action, $object = null, $when = null, $where = null) {

        $admins = (array) \Gini\Config::get('admin.token');
        if (in_array($this->username, $admins)) {
            return true;
        }

        if ($object === null) {
            return \Gini\Event::trigger(
                "user.isAllowedTo[$action]",
                $this, $action, null, $when, $where);
        }

        $oname = is_string($object) ? $object : $object->name();

        return \Gini\Event::trigger(
            [
                "user.isAllowedTo[$action].$oname",
                "user.isAllowedTo[$action].*"
            ],
            $this, $action, $object, $when, $where);
    }

    public function icon()
    {
        return new \Model\Icon($this);
    }

    public function save()
    {
        if ($this->ctime == '0000-00-00 00:00:00' || !$this->ctime) $this->ctime = date('Y-m-d H:i:s');
        return parent::save();
    }

}
