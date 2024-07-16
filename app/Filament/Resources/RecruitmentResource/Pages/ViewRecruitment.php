<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use App\Filament\Resources\RecruitmentResource;
use Filament\Resources\Pages\ViewRecord;

class ViewRecruitment extends ViewRecord
{
    protected static string $resource = RecruitmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
