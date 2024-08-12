<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\UserApplyJob;
use Filament\Widgets\Widget;
use App\Enums\StageRecruitment;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use App\Infolists\Components\LivewireEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use App\Livewire\Widget\StageRecruitmentTable as WidgetStageRecruitmentTable;


class StageRecruitmentTable extends Widget implements HasInfolists, HasForms
{
    use InteractsWithForms, InteractsWithInfolists;

    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.custom-table-recruitments';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                LivewireEntry::make('table-widget-recruitments')
                    ->livewire(WidgetStageRecruitmentTable::class)
            ]);
    }
}
