<?php

namespace App\Filament\Resources\RecruitmentResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Request;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RecruitmentResource;

class ListRecruitments extends ListRecords
{
    protected static string $resource = RecruitmentResource::class;

    protected static string $view = 'filament.resources.recruitment-resource.pages.custom-list-recruitment';
    // public function getFilteredTableQuery(): Builder
    // {
    //     $query = parent::getTableQuery();

    //     $filter = Request::query('filter');
    //     if ($filter) {
    //         switch ($filter) {
    //             case 'Activity_1l23d1f': //filter data pelamar belum di validasi -> Review Data Diri
    //                 return $query->where('is_valid', null);
    //                 break;
    //             case 'Event_0dnse37': // filter data pelamar ditolak -> Notifikasi Tidak Lulus Seleksi
    //                 return $query->where('is_valid', 'no');
    //                 break;
    //             case 'Activity_1h0pos4': // filter data pelamar yang di lulus tahap seleksi data diri -> input jadwal interview User
    //                 return $query->where('is_valid', 'yes')->whereDoesntHave('interview');
    //                 break;
    //             case 'Activity_1frsqol': // filter data pelamar yang di undang wawancara user -> Input Hasil Interview User
    //                 return $query->whereHas(
    //                     'interview',
    //                     fn (Builder $subQuery) =>
    //                     $subQuery->where('is_invited', 'yes')->where('interview_type', 'user')->whereDoesntHave('interviewResult')
    //                 );
    //                 break;
    //             case 'Event_1yf59cf': // filter data pelamar tidak lulus tahap wawancara user -> Notifikasi Tidak Lulus Interview User
    //                 return $query->whereHas('interview', function (Builder $subQuery) {
    //                     $subQuery->where('is_invited', 'yes')
    //                         ->where('interview_type', 'user')
    //                         ->whereHas('interviewResult', fn (Builder $subQuery2) => $subQuery2->where('is_success', 'no'));
    //                 })->has('interview', '=', 1);
    //                 break;
    //             case 'Activity_0y70sst': // filter data pelamar lulus tahap wawancara user -> Input jadwal Interview HR
    //                 return $query->whereHas('interview', function (Builder $subQuery) {
    //                     $subQuery->where('is_invited', 'yes')
    //                         ->where('interview_type', 'user')
    //                         ->whereHas('interviewResult', fn (Builder $subQuery2) => $subQuery2->where('is_success', 'yes'));
    //                 })->has('interview', '=', 1);
    //                 break;
    //             case 'Activity_0qi07gz': // filter data pelamar yang diundang wawancara hr -> Input Hasil Interview HR
    //                 return $query->whereHas(
    //                     'interview',
    //                     fn (Builder $subQuery) =>
    //                     $subQuery->where('is_invited', 'yes')->where('interview_type', 'hr')->whereDoesntHave('interviewResult')
    //                 );
    //                 break;
    //             case 'Event_00wxbct': // filter data pelamar tidak lulus tahap wawancara hr -> Notifikasi Tidak Lulus Interview HR
    //                 return $query->whereHas('interview', function (Builder $subQuery) {
    //                     $subQuery->where('is_invited', 'yes')
    //                         ->where('interview_type', 'hr')
    //                         ->whereHas('interviewResult', fn (Builder $subQuery2) => $subQuery2->where('is_success', 'no'));
    //                 });
    //                 break;
    //             case 'Activity_00fjfo4': // filter data pelamar lulus tahap wawancara hr -> Input Hasil Akhir
    //                 return $query->whereHas('interview', function (Builder $subQuery) {
    //                     $subQuery->where('is_invited', 'yes')
    //                         ->where('interview_type', 'hr')
    //                         ->whereHas('interviewResult', fn (Builder $subQuery2) => $subQuery2->where('is_success', 'yes'));
    //                 });
    //                 break;
    //                 // case 'Gateway_1awh5yc':
    //                 //     $query->where('column_name', 'Exclusive Gateway');
    //                 //     break;
    //             default:
    //                 return $query;
    //                 break;
    //         }
    //     }

    //     return $query;
    // }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
