<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\RecruitmentResource;

class ViewRecruitment extends ViewRecord
{
    protected static string $resource = RecruitmentResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('Edit')
                    ->icon('heroicon-s-eye')
                    ->url(fn (Model $record) => EditRecruitment::getUrl([$record->id]))
        ];
    }
}
