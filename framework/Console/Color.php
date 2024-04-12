<?php

namespace Framework\Console;

enum Color: string
{
    case default = '0;39';
    case white = '1;37';
    case black = '0;30';
    case red = '0;31';
    case light_red = '1;31';
    case green = '0;32';
    case blue = '0;34';
    case magenta = '0;35';
    case yellow = '0;33';
    case cyan = '0;36';
    case gray = '1;30';
    case light_gray = '0;37';
}
