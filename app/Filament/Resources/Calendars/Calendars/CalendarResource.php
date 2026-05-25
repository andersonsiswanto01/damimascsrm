<?php

namespace App\Filament\Resources\Calendars\Calendars;

use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Calendars\Pages\ListCalendars;
use App\Filament\Resources\Calendars\Pages\CreateCalendar;
use App\Filament\Resources\Calendars\Pages\EditCalendar;
use App\Filament\Resources\Calendars\Pages;
use App\Filament\Resources\Calendars\RelationManagers;
use App\Models\Calendar;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resource\Calendars\Widgets;

class CalendarResource extends Resource
{
    protected static ?string $model = Calendar::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-c-calendar-days';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }


    public static function table(Table $table): Table
    {
        if (auth()->user()->cannot('viewAny', Order::class)) {
            return $table->columns([]); // Hides all columns
        }
        
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // Tables\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCalendars::route('/'),
            'create' => CreateCalendar::route('/create'),
            'edit' => EditCalendar::route('/{record}/edit'),
        ];
    }
}
