<?php

namespace App\Enums;

enum VideoSourceType: string
{
    case Upload   = 'upload';
    case Facebook = 'facebook';
    case TikTok   = 'tiktok';
}
