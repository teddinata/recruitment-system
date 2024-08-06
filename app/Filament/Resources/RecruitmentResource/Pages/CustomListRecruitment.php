<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use App\Livewire\WorkflowRecruitment;
use Illuminate\Database\Eloquent\Model;
use App\Infolists\Components\LivewireEntry;
use App\Filament\Resources\RecruitmentResource;


class CustomListRecruitment extends Page
{
    protected static string $resource = RecruitmentResource::class;

    protected static string $view = 'filament.resources.recruitment-resource.pages.custom-list-recruitment';

    protected static ?string $title = 'List Recruitments';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('add_candidate')
                    ->url(fn () => CreateRecruitment::getUrl())
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                LivewireEntry::make('table-filter-recruitment')
                    ->livewire(WorkflowRecruitment::class)
            ]);
    }
}
