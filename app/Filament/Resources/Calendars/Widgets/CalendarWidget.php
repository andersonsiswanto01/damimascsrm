<?php

namespace App\Filament\Resources\Calendars\Widgets;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\DateTimePicker;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Filament\Schemas\Schema;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;
use App\Filament\Resources\Calendars\Calendars\CalendarResource;

class CalendarWidget extends FullCalendarWidget
{

    public string | Model | null $model = Calendar::class;
    
    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridMonth,timeGridWeek,timeGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('title'),

            Textarea::make('description')
                ->rows(3)
                ->columnSpanFull(),

            Grid::make()
                ->schema([
                    DateTimePicker::make('start_time'),

                    DateTimePicker::make('end_time'),
                ]),
        ];
    }

protected function headerActions(): array
 {
     return [
         CreateAction::make()
             ->mountUsing(
                 function (Schema $schema, array $arguments) {
                     $schema->fill([
                         'start_time' => $arguments['start'] ?? null,
                         'end_time' => $arguments['end'] ?? null
                     ]);
                 }
             )
     ];
 }

    protected function modalActions(): array
 {
     return [
         EditAction::make()
             ->mountUsing(
                 function (Calendar $record, Schema $schema, array $arguments) {
                     $schema->fill([
                        'id'=> $record->id,
                         'title' => $record->title,
                         'description' => $record->description,
                         'name' => $record->name,
                        'start_time' => $arguments['event']['start'] ?? $record->starts_at,
                         'end_time' => $arguments['event']['end'] ?? $record->ends_at
                     ]);
                 }
             ),
         DeleteAction::make(),
     ];
 }

    
    public function fetchEvents(array $fetchInfo): array
    {
        return Calendar::query()
            ->where('start_time', '>=', $fetchInfo['start'])
            ->where('end_time', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Calendar $event) => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_time,
                    'end' => $event->end_time,
                    'description' => $event->description,
                             ]
            )
            ->all();
    }

    
}
