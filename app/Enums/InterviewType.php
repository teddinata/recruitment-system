<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum InterviewType: string implements HasLabel
{
    case USER = "user";
    case HR = "hr";
    
    public function getLabel(): ?string
    {
        return match ($this) {

            self::USER => 'user',

            self::HR => 'hr',

        };
    }
}
