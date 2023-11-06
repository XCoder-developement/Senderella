<?php

namespace App\Http\Enums;

use App\Traits\Enum\EnumToArray;

enum VerificationTypeEnum: int {

    use EnumToArray;

    case phone = 0;
    case email = 1;
}
