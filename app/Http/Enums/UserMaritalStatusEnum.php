<?php

namespace App\Http\Enums;

use App\Traits\Enum\EnumToArray;

enum UserMaritalStatusEnum: int {

    use EnumToArray;

    case single = 0;
    case married = 1;
}
