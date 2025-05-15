<?php

namespace App\Filament\Resources\CalendarResource\Widgets;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;
use App\Filament\Resources\CalendarResource;

class CalendarWidget extends FullCalendarWidget
{

    public string | Model | null $model = Calendar::class;
    
    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('title'),

            Forms\Components\Textarea::make('description')
                ->rows(3)
                ->columnSpanFull(),

            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\DateTimePicker::make('start_time'),

                    Forms\Components\DateTimePicker::make('end_time'),
                ]),
        ];
    }

    protected function modalActions(): array
 {
     return [
         Actions\EditAction::make()
             ->mountUsing(
                 function (Calendar $record, Forms\Form $form, array $arguments) {
                     $form->fill([
                        'id'=> $record->id,
                         'title' => $record->title,
                         'description' => $record->description,
                         'name' => $record->name,
                         'start_time' => $arguments['event']['start'] ?? $record->starts_at,
                         'end_time' => $arguments['event']['end'] ?? $record->ends_at
                     ]);
                 }
             ),
         Actions\DeleteAction::make(),
     ];
 }

    
    public function fetchEvents(array $fetchInfo): array
    {
        return Calendar::query()
            ->where('start_time', '>=', $fetchInfo['start'])
            ->where('end_time', '<=', $fetchInfo['end'])
            ->get()
            ->map(


                fn (Calendar $event) => Calendar::make()
                ->id($event->id)
                ->title($event->title)
                ->start($event->start_time)
                ->end($event->end_time)
                ->description($event->description),

        )
            //     fn (Calendar $event) => [
            //         'id' => $event->id,
            //         'title' => $event->title,
            //         'start' => $event->start_time,
            //         'end' => $event->end_time,
            //         'description' => $event->description,
            //                  ]
            // )
            ->all();
    }

    
}
