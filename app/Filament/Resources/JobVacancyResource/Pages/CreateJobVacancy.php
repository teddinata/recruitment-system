<?php

namespace App\Filament\Resources\JobVacancyResource\Pages;

use App\Filament\Resources\JobVacancyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJobVacancy extends CreateRecord
{
    protected static string $resource = JobVacancyResource::class;

    public static function formActions()
    {
        return [
            Actions\Button::make('save')
                ->label('Save')
                ->action(static function () {
                    return [
                        'redirect' => '/admin/job-vacanies',
                    ];
                }),
        ];
    }
}
