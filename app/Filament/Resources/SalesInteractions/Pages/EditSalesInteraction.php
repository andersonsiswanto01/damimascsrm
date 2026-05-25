<?php

namespace App\Filament\Resources\SalesInteractions\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SalesInteractions\SalesInteractions\SalesInteractionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;

class EditSalesInteraction extends EditRecord
{
    protected static string $resource = SalesInteractionResource::class;

    protected function getHeaderActions(): array
{
    return [
        DeleteAction::make(),

        Action::make('updateAuthor')
            ->label('Update Author')
            ->schema([
                Select::make('customer_data')
                    ->label('Author')
                    ->required(),
            ])
            ->action(function (array $data, Post $record): void {
                $record->author()->associate($data['customer_data']);
                $record->save();
            }),
    ];
}

}
