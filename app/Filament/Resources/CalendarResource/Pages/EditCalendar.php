<?php

namespace App\Filament\Resources\CalendarResource\Pages;

use App\Filament\Resources\CalendarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Calendar;
use Filament\Forms;

class EditCalendar extends EditRecord
{
    protected static string $resource = CalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function modalActions(): array
 {
     return [
         Actions\EditAction::make()
             ->mountUsing(
                 function (Calendar $record, Forms\Form $form, array $arguments) {
                     $form->fill([
                         'name' => $record->name,
                         'starts_at' => $arguments['event']['start'] ?? $record->starts_at,
                         'ends_at' => $arguments['event']['end'] ?? $record->ends_at
                     ]);
                 }
             ),
         Actions\DeleteAction::make(),
     ];
 }

}
