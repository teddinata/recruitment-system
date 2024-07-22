<?php

namespace App\Filament\Resources\RecruitmentResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class InterviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'interview';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('interview_date')
                //     ->required()
                //     ->maxLength(255),
                DateTimePicker::make('interview_date')
                    ->native(false)
                    ->hoursStep(2)
                    ->minutesStep(15)
                    ->secondsStep(10)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('interview_date')
            ->columns([
                Tables\Columns\TextColumn::make('interview_date'),
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
