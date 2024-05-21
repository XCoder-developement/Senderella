<?php

namespace App\Http\Enums;

use App\Traits\Enum\EnumToArray;

enum NotificationTypeEnum: int
{

    use EnumToArray;


    case DASHBOARD = 0;
    case VIEW = 1;
    case LIKE = 2;
    case NEWPOST = 3;
    case CHAT = 4;
    case BOOKMARK = 5;
    case DISLIKE = 6;
    case PREMIEM = 7;
    case SHOWUSERIMAGE = 8;
    case SECONDCHANCE = 9;
    case BLOCK = 10;


    public static function getTitle($value)
    {
        switch ($value) {


            case 1:
                return 'view';
            case 2:
                return 'like partner';
            case 3:
                return 'new post';
            case 4:
                return 'chat';
            case 5:
                return 'bookmark';
            case 6:
                return 'dislike';
            case 7:
                return 'premiem';
            case 8:
                return 'show user image';
            case 9:
                return 'second chance';
            default:
                return 'dashboard'; // Handle the default case as needed
        }
    }
}
