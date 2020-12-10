<?php

namespace App\Models\Enum;

class UserEnum
{
    // 状态类别
    const INVALID = -1; //已删除
    const NORMAL = 0; //正常
    const FREEZE = 1; //冻结

    public static function getStatusName($status)
    {
        switch ($status) {
            case self::INVALID:
                return 'deleted';
            case self::NORMAL:
                return 'published';
            case self::FREEZE:
                return 'draft';
            default:
                return '正常';
        }
    }
}
