<?php

namespace Gini\Model;

class Role
{
    public static function userACL($e, $user, $action, $object, $when, $where)
    {
        switch ($action) {
            case '管理':
                if ($user->is_admin) {
                    $e->abort();

                    return true;
                }
                break;
            case '修改基本信息':
                if ($user->is_admin) {
                    $e->abort();

                    return true;
                }
                if ($user->id == $object->id) {
                    $e->abort();

                    return true;
                }
                break;
            default:
                $e->pass();

                return false;
                break;
        }
    }
}
