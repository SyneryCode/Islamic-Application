<?php

namespace App\Enums;

enum HabitStatus: string
{
    case IN_PROGRESS = 'in_progress';   
    case EXPIRED     = 'expired';       
    case COMPLETED   = 'completed';     
}
