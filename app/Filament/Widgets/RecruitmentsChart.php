<?php

namespace App\Filament\Widgets;

use App\Enums\StatusRecruitment;
use App\Models\UserApplyJob;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class RecruitmentsChart extends ChartWidget
{

    protected static string $color = 'info';

    // public ?string $filter = 'today';

    protected int|string|array $columnSpan = 'full';

    // protected static ?string $maxHeight = '1000px';

    // protected static ?string $maxWidth = '300px';

    protected function getData(): array
    {
        $query = New UserApplyJob;

        $reviewDataDiri = $query->where('is_valid', 'no')->orWhere('is_valid', null)->get()->count();

        $undangWawancaraUser = $query->where('is_valid', 'yes')->get()->count();
        
        $hasilWawancaraUser = $query->whereHas(
            'interview',
            fn (Builder $subQuery) =>
            $subQuery->where('is_invited', 'yes')
                ->where('interview_type', 'user')
                ->where(function (Builder $nestedQuery) {
                    $nestedQuery->whereDoesntHave('interviewResult')
                        ->orWhereHas('interviewResult', function (Builder $resultQuery) {
                            $resultQuery->where('is_success', 'no');
                        });
                })
        )->get()->count();

        $undangWawancaraHR = $query->whereHas('interview', function (Builder $subQuery) {
            $subQuery->where('is_invited', 'yes')
                ->where('interview_type', 'user')
                ->whereHas('interviewResult', fn (Builder $subQuery2) => $subQuery2->where('is_success', 'yes'));
        })->has('interview', '=', 1)->get()->count();

        $hasilWawancaraHR = $query->whereHas(
            'interview',
            fn (Builder $subQuery) =>
            $subQuery->where('is_invited', 'yes')
            ->where('interview_type', 'hr')
            ->where(function (Builder $nestedQuery){
                $nestedQuery->whereDoesntHave('interviewResult')
                ->orWhereHas('interviewResult', function (Builder $resultQuery){
                    $resultQuery->where('is_success', 'no');
                });
            })
        )->get()->count();

        $hasilAkhir = $query->whereIn('acceptance_status', [StatusRecruitment::REJECTED->value, StatusRecruitment::ACCEPTED->value])
        ->orWhereHas('interview', function (Builder $subQuery) {
            $subQuery->where('is_invited', 'yes')
                ->where('interview_type', 'hr')
                ->whereHas('interviewResult', fn (Builder $subQuery2) => $subQuery2->where('is_success', 'yes'));
        })->get()->count();

        $data = [$reviewDataDiri, $undangWawancaraUser, $hasilWawancaraUser, $undangWawancaraHR, $hasilWawancaraHR, $hasilAkhir];

        $labels = app()->getLocale() == 'id' ? ['Review Data Diri', 'Undang Wawancara User', 'Hasil Wawancara User', 'Undang Wawancara HR', 'Hasil Wawancara HR', 'Hasil Akhir'] :
        ['Document Screening', 'Invit to User Interview', 'User Interview Result', 'Invite to HR Interview', 'HR Interview Result', 'Final Decision'];
        // dd($data);

        return [
            'datasets' => [
                [
                    'label' => 'Customers',
                    'data' => $data,
                    'fill' => 'start',
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }


    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
// protected static ?string $heading = 'JUMLAH PELAMAR BY TAHAP';
    public function getHeading(): string|Htmlable|null
    {
        return app()->getLocale() == 'id' ? 'JUMLAH PELAMAR BY TAHAP' : 'NUMBER OF APPLICANTS BY STAGE';
    }
}
