<?php

namespace App\Filament\Resources\CompletedRecruitmentResource\Pages;

use App\Filament\Resources\CompletedRecruitmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCompletedRecruitments extends ManageRecords
{
    protected static string $resource = CompletedRecruitmentResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
