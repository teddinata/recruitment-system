<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\LanguageLine;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action;

class Setting extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.settings';

    public array $langData = [];

    public $modelLang;

    public function mount(): void
    {
        $this->modelLang = LanguageLine::first();
        $this->form->fill($this->modelLang->toArray());
    }
    public function form(Form $form): Form
    {
        return $form
            ->statePath('langData')
            ->schema([
                Section::make('Select Language')
                    ->description("")
                    ->schema([
                        Select::make('kode_country')
                        ->options([
                            'en' => 'English',
                            'id' => 'Indonesian',
                        ])
                    ])
                    ->aside(),
                Actions::make([
                    Action::make('saveBahasa')
                        ->label('Save')
                        ->successNotificationTitle('Saved!')
                        ->failureNotificationTitle('Data could not be saved.')
                        ->action(function () {
                            $formData = $this->form->getState();
                            // dd($formData);
                            try {
                                $this->modelLang->update($formData);
                                // app()->setLocale($formData['kode_country']);
                                $this->notif();
                            } catch (\Throwable $th) {
                                throw $th;
                            }
                        }),
                ])->alignLeft(),
            ]);
    }

    public function notif()
    {
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
