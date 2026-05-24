<?php

namespace App\Filament\Resources\Calendars\Pages;

use App\Filament\Resources\Calendars\CalendarResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Pages\ListRecords; // ✅ Correct Parent Class
use App\Filament\Resources\Calendars\Widgets\CalendarWidget; // ✅ Import CalendarWidget
use App\Models\Calendar;

class ListCalendars extends ListRecords
{
    protected static string $resource = CalendarResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class, // ✅ Ensure CalendarWidget is correctly imported
        ];
    }
    
    protected function shouldDisplayTable(): bool
{
    return false;
}

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('newActivity')
                ->label('New Activity')
                ->modalHeading('Create New Activity')
                ->modalButton('Create')
                ->form([
                    TextInput::make('title')
                        ->label('Activity Title')
                        ->required(),

                    Textarea::make('description')
                        ->label('Description')
                        ->rows(3),

                    DateTimePicker::make('start_time')
                        ->label('Start Time')
                        ->required(),

                    DateTimePicker::make('end_time')
                        ->label('End Time')
                        ->required(),
                ])
                ->action(fn (array $data) => Calendar::create($data)),
        ];
    }
}
