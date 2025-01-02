<?php

namespace App\Enums\Users;

use App\Enums\EnumToArray;
use App\Enums\WithLabel;

enum Status: string implements WithLabel {
    use EnumToArray;

    case REGISTERED = 'REGISTERED';
    case PROVISIONAL = 'PROVISIONAL';
    case BANNED = 'BANNER';
    case DELETED = 'DELETED';

    public function label(): string
    {
        return match ($this) {
            self::REGISTERED => '正会員',
            self::PROVISIONAL => '仮会員',
            self::BANNED => '停止中',
            self::DELETED => '退会済',
        };
    }
}
