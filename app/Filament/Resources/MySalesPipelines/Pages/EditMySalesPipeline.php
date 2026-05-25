<?php

namespace App\Filament\Resources\MySalesPipelines\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\MySalesPipelines\MySalesPipelines\MySalesPipelineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Livewire;
use Livewire\Attributes\On;

class EditMySalesPipeline extends EditRecord
{
    protected static string $resource = MySalesPipelineResource::class;

    // #[On('refreshSalesStageStepper')] // ✅ Listen for the event
    // public function refreshStepper()
    // {
    //     dd('test');
    //     $this->refreshFormData(['current_stage_id']); // ✅ Refresh the stage field in the form
    // }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    


}
