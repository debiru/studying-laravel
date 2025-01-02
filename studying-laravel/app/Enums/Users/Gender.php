<?php

namespace App\Enums\Users;

use App\Enums\EnumToArray;
use App\Enums\WithLabel;

enum Gender: string implements WithLabel {
    use EnumToArray;

    case UNSET = 'UNSET';
    case MALE = 'MALE';
    case FEMALE = 'FEMALE';

    public function label(): string
    {
        return match ($this) {
            self::UNSET => '未設定',
            self::MALE => '男性',
            self::FEMALE => '女性',
        };
    }
}
