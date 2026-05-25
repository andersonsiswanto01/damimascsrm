<?php

namespace App\Filament\Resources\OrderMasters\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\OrderMasters\OrderMasters\OrderMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderMaster extends EditRecord
{
    protected static string $resource = OrderMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function mount($record): void
{
    parent::mount($record);

}
    protected function mutateFormDataBeforeFill(array $data): array
{
    // $this->record is the OrderMaster model being edited
    $data['add_payment'] = $this->record
        ? $this->record->payments()->exists()
        : false;

    return $data;
}

}
