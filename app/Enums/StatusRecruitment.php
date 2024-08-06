<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusRecruitment: string implements HasColor, HasIcon, HasLabel
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case FAILED = 'failed';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACCEPTED => 'Accepted',
            self::REJECTED => 'Rejected',
            self::FAILED => 'Failed',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::ACCEPTED =>'success',
            self::REJECTED => 'danger',
            self::FAILED => 'default',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-m-arrow-path',
            self::ACCEPTED => 'heroicon-m-check-badge',
            self::REJECTED => 'heroicon-m-x-circle',
            self::FAILED => 'heroicon-m-x-circle',
        };
    }
}
