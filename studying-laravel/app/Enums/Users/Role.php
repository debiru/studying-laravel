<?php

namespace App\Enums\Users;

use App\Enums\EnumToArray;
use App\Enums\WithLabel;

enum Role: string implements WithLabel {
    use EnumToArray;

    case USER = 'USER';
    case OWNER = 'OWNER';

    public function label(): string
    {
        return match ($this) {
            self::USER => '一般ユーザー',
            self::OWNER => '施設管理者',
        };
    }
}
