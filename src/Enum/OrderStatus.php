<?php

namespace App\Enum;

enum OrderStatus: string
{
    case CART = 'cart';
    case VALIDATED = 'validated';
}
