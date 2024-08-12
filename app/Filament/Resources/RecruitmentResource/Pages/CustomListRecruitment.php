<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use App\Livewire\WorkflowRecruitment;
use Illuminate\Database\Eloquent\Model;
use App\Infolists\Components\LivewireEntry;
use App\Filament\Resources\RecruitmentResource;
use Illuminate\Contracts\Support\Htmlable;

class CustomListRecruitment extends Page
{
    protected static string $resource = RecruitmentResource::class;

    protected static string $view = 'filament.resources.recruitment-resource.pages.custom-list-recruitment';

    

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
            ->label(app()->getLocale() == 'id' ? 'Buat Baru' : 'Create New')
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
    // protected static ?string $title = 'List Recruitments';
    public function getTitle(): string|Htmlable
    {
        $locale = app()->getLocale();
        if($locale == 'id'){
            $result = 'Daftar Rekruitmen';
        }else{
            $result = 'List Recruitments';
        }
        return $result;
    }
}
