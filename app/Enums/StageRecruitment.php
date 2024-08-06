<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StageRecruitment: string implements HasColor, HasLabel
{
    case DS = 'document_screening';
    case DSC = 'document_screening(completed)';
    case UI = 'user_interview';
    case UIC = 'user_interview(completed)';
    case HI = 'hr_interview';
    case HIC = 'hr_interview(completed)';
    case FD = 'final_decision';
    case FDC = 'final_decision(completed)';

    public function getLabel(): string
    {
        return match ($this) {
            self::DS => 'Document Screening',
            self::DSC => 'Document Screening(completed)',
            self::UI => 'User Interview',
            self::UIC => 'User Interview(completed)',
            self::HI => 'HR Interview',
            self::HIC => 'HR Interview(completed)',
            self::FD => 'Final Decision',
            self::FDC => 'Final Decision(completed)',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::DS => 'warning',
            self::DSC => 'success',
            self::UI => 'warning',
            self::UIC => 'success',
            self::HI => 'warning',
            self::HIC => 'success',
            self::FD => 'warning',
            self::FDC => 'success',
        };
    }
}

