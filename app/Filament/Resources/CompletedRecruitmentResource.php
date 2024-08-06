<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Tables\Table;
use App\Models\UserApplyJob;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use App\Models\CompletedRecruitment;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompletedRecruitmentResource\Pages;
use App\Filament\Resources\CompletedRecruitmentResource\RelationManagers;

class CompletedRecruitmentResource extends Resource
{
    protected static ?string $model = UserApplyJob::class;

    protected static ?string $slug = 'ongoing/completed-recruitments';

    protected static ?string $label = 'Completed Recruitments';

    protected static ?string $navigationGroup = 'Ongoing';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        $query = UserApplyJob::whereIn('acceptance_status', ['failed', 'rejected', 'accepted']);
;
        // dd($userApplyJobs);
        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('jobVacancy.title')
                    ->label('Job Position')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cv_path')
                    ->label('Resume Attachment')
                    ->formatStateUsing(function ($state) {
                        return '<a href="' . asset('storage/' . $state) . '" target="_blank">Lihat CV</a>';
                    })
                    ->html()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->state(function ($record) {
                        return $record->first_name . ' ' . $record->last_name;
                    })
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Gender')
                    ->formatStateUsing(fn ($state) => ($state == 1) ? "Laki-Laki" : "Perempuan"),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('acceptance_status')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_stage')
                    ->label('Current Stage')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Phone Number')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('place_and_date_birth')
                    ->label('Place, Date of Birth')
                    ->getStateUsing(function ($record) {
                        return $record->place_of_birth . ', ' . $record->date_of_birth;
                    })
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('old_company')
                    ->label('Old Company')
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->persistSearchInSession()
            ->filters([
                //
            ])
            ->toggleColumnsTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Toggle columns'),
            )
            ->actions([
                Tables\Actions\ViewAction::make()
                // ->hidden(true),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pelamar')
                    // ->description(function ($record){
                    //     $record->acceptend_status == 'Pending'
                    // })
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('full_name')
                                        ->label('Nama Lengkap')
                                        ->state(function ($record) {
                                            return $record->first_name . ' ' . $record->last_name;
                                        }),
                                    Infolists\Components\TextEntry::make('gender')
                                        ->label('Jenis Kelamin')
                                        ->formatStateUsing(fn ($state) => ($state == 1) ? 'Laki-Laki' : 'Perempuan'),
                                    Infolists\Components\TextEntry::make('email'),
                                    Infolists\Components\TextEntry::make('phone_number')
                                        ->label('Nomor Telepon'),
                                    Infolists\Components\TextEntry::make('address')
                                        ->label('Alamat'),
                                    Infolists\Components\TextEntry::make('birth_information')
                                        ->label('Tempat, Tanggal Lahir')
                                        ->state(function ($record) {
                                            return $record->place_of_birth . ', ' . $record->date_of_birth;
                                        }),
                                ]),
                                Infolists\Components\Group::make([
                                    Infolists\Components\TextEntry::make('education')
                                        ->label('Pendidikan Terakhir'),
                                    Infolists\Components\TextEntry::make('major')
                                        ->label('Jurusan'),
                                    Infolists\Components\TextEntry::make('join_date')
                                        ->label('Tanggal Mulai Kerja'),
                                    Infolists\Components\TextEntry::make('linkedin_url')
                                        ->label('Link Linked'),
                                    Infolists\Components\TextEntry::make('job_source')
                                        ->label('Sumber Informasi Loker'),
                                    Infolists\Components\TextEntry::make('old_company')
                                        ->label('Perusahaan Sebelumnya'),
                                ]),
                                Infolists\Components\ImageEntry::make('cv_path')
                                    ->label('Lampiran Peralamar')
                                    ->grow(false),
                            ]),
                        Infolists\Components\Fieldset::make('Tentang Diri Pelamar')
                            ->schema([
                                Infolists\Components\TextEntry::make('self_description')
                                    ->hiddenLabel()
                                    ->prose()
                                    ->markdown()
                                    ->html(),
                            ])
                    ]),
                Infolists\Components\Section::make('Pekerjaan Yang Dilamar')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('jobVacancy.title')
                                            ->label('Posisi Pekerjaan'),
                                        Infolists\Components\TextEntry::make('jobVacancy.work_hours')
                                            ->label('Jam Kerja'),
                                        Infolists\Components\TextEntry::make('jobVacancy.location')
                                            ->label('Posisi Pekerjaan'),
                                    ]),
                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('jobVacancy.experience')
                                            ->label('Level Pekerjaan'),
                                        Infolists\Components\IconEntry::make('jobVacancy.remote')
                                            ->label('Remote?')
                                            ->icon(fn (string $state): string => match ($state) {
                                                '1' => 'heroicon-o-check-circle',
                                                '0' => 'heroicon-o-x-circle',
                                            })
                                            ->color(fn (string $state): string => match ($state) {
                                                '1' => 'success',
                                                '0' => 'danger',
                                            }),

                                        Infolists\Components\TextEntry::make('jobVacancy.location')
                                            ->label('Posisi Pekerjaan'),
                                    ]),
                                Infolists\Components\ImageEntry::make('jobVacancy.image')
                                    ->label('Gambar')
                            ]),
                        Infolists\Components\Fieldset::make('Tentang Pekerjaan')
                            ->schema([
                                Infolists\Components\TextEntry::make('jobVacancy.description')
                                    ->label('Deskripsi :')
                                    ->html(),
                                Infolists\Components\TextEntry::make('jobVacancy.qualifications')
                                    ->label('Kualifikasi :')
                                    ->html(),
                            ])->columns(2)
                    ])
                    ->collapsible(),
                Infolists\Components\Section::make('Data Wawancara Pelamar')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\RepeatableEntry::make('interview')
                                    ->hiddenLabel()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('interview_type')
                                            ->label('Jenis Wawancara')
                                            ->formatStateUsing(fn ($state) => $state == 'user' ? 'User' : 'HR'),
                                        Infolists\Components\TextEntry::make('interview_date')
                                            ->label('Tanggal Wawancara')
                                            ->dateTime('d M, Y \J\a\m H:i \-\ \S\e\l\e\s\a\i', 'Asia/Jakarta'),
                                        Infolists\Components\TextEntry::make('google_meet_link')
                                            ->label('Link Meeting'),
                                        Infolists\Components\TextEntry::make('is_invited')
                                            ->label('Apakah Di undang?')
                                            ->formatStateUsing(fn ($state) => $state == 'yes' ? 'Diundang' : 'Ditolak')
                                            ->icon(fn (string $state): string => match ($state) {
                                                'yes' => 'heroicon-o-check-circle',
                                                'no' => 'heroicon-o-x-circle',
                                            })
                                            ->color(fn (string $state): string => match ($state) {
                                                'yes' => 'success',
                                                'no' => 'danger',
                                            }),
                                        Infolists\Components\TextEntry::make('notes')
                                            ->label('Catatan Wawancara')
                                            ->prose()
                                            ->markdown(),

                                        Infolists\Components\Fieldset::make('Hasil Wawancara')
                                            ->schema([
                                                Infolists\Components\TextEntry::make('interviewResult.is_success')
                                                    ->label('Apakah Lulus Wawancara')
                                                    ->formatStateUsing(fn ($state) => $state == 'yes' ? 'Lulus' : 'Gagal')
                                                    ->icon(fn (string $state): string => match ($state) {
                                                        'yes' => 'heroicon-o-check-circle',
                                                        'no' => 'heroicon-o-x-circle',
                                                    })
                                                    ->color(fn (string $state): string => match ($state) {
                                                        'yes' => 'success',
                                                        'no' => 'danger',
                                                    }),
                                                Infolists\Components\TextEntry::make('interviewResult.review')
                                                    ->label('Keterangan Hasil Wawancara')
                                            ])->columns(1)
                                    ])
                                    ->columnSpanFull()
                                    ->grid(2),

                            ]),
                    ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCompletedRecruitments::route('/'),
        ];
    }
}
