<?php

namespace App\Filament\Resources\Calendars\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use App\Filament\Resources\Calendars\Calendars\CalendarResource;
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
            DeleteAction::make(),
        ];
    }

    protected function modalActions(): array
 {
     return [
         EditAction::make()
             ->mountUsing(
                 function (Calendar $record, Schema $schema, array $arguments) {
                     $schema->fill([
                         'name' => $record->name,
                         'starts_at' => $arguments['event']['start'] ?? $record->starts_at,
                         'ends_at' => $arguments['event']['end'] ?? $record->ends_at
                     ]);
                 }
             ),
         DeleteAction::make(),
     ];
 }

}
