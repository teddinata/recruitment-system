<?php

namespace App\Filament\Resources\RecruitmentResource\RelationManagers;

use App\Enums\InterviewType;
use App\Models\Interview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InterviewRelationManager extends RelationManager
{
    protected static string $relationship = 'interview';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('interview_type')
                            ->options(fn () => array_column(InterviewType::cases(), "name", "value"))
                            ->native(false),
                        Forms\Components\DateTimePicker::make('interview_date')
                            ->label('Tanggal Wawancara')
                            ->native(false)
                            // ->required()
                            ->hoursStep(2)
                            ->minutesStep(15)
                            ->secondsStep(10),
                        // visible only if is_invited is true
                        // ->visible(fn ($get) => $get('is_invited') === true),
                        Forms\Components\TextInput::make('google_meet_link')
                            ->url()
                            ->label('Link Concall')
                            // ->required()
                            ->placeholder('Enter old company')
                            ->rules(['max:255'])
                        // visible only if is_invited is true
                        ,
                        Forms\Components\Textarea::make('notes')
                            ->label('Keterangan')
                            ->rows(10)
                            ->cols(20),
                    ])->columnSpan(2),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Radio::make('is_invited')
                            ->label("Apakah Undang Wawancara?")
                            ->required()
                            ->options([
                                'yes' => 'Ya',
                                'no' => 'Tidak',
                            ])
                            ->reactive()
                            ->inline()
                            ->inlineLabel(false),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('is_invited')
            ->recordTitle(fn (Interview $record): string => "Interview(Id: {$record->id})")
            ->columns([
                Tables\Columns\TextColumn::make('interview_type')
                    ->formatStateUsing(fn ($state) => $state == 'user' ? 'User' : 'HR'),
                Tables\Columns\TextColumn::make('is_invited')
                    ->label('Invited?')
                    ->badge()
                    ->color(fn ($state) => $state == 'yes' ? 'success' : 'gray')
                    ->formatStateUsing(fn ($state) => $state == "yes" ? 'Invited' : 'Not Invited'),
                Tables\Columns\TextColumn::make('interview_date')
                    ->label('Interview Date'),
                Tables\Columns\TextColumn::make('interview_date')
                    ->label('Interview Date'),
                Tables\Columns\TextColumn::make('google_meet_link')
                    ->label('Link Concall'),
                Tables\Columns\TextColumn::make('interviewResult.is_success')
                    ->label('Interview Result')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state == "yes" ? 'Passed' : 'Failed')
                    ->color(fn ($state) => $state == "yes" ? 'success' : 'danger'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
