<?php

namespace App\Filament\Resources\SalesInteractionResource\Pages;

use App\Filament\Resources\SalesInteractionResource;
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
        Actions\DeleteAction::make(),

        Action::make('updateAuthor')
            ->label('Update Author')
            ->form([
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
