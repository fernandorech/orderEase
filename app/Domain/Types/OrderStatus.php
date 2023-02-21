<?php

namespace App\Domain\Types;

Enum OrderStatus: string
{
    case Submitted = 'submitted';
    case Delivered = 'delivered';
    case Error = 'error';

}
