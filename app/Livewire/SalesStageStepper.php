<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SalesPipeline;
use App\Models\SalesStage;
use Livewire\Attributes\On;

class SalesStageStepper extends Component
{
    public $salesStages;
    public $currentStageId;
    public $record; // Store the record

    protected $listeners = ['refreshSalesStageStepper' => '$refresh'];

    public function mount($record)
    {
        $this->record = $record; // ✅ Store the record
        $this->salesStages = SalesStage::all(); // ✅ Fetch all sales stages
        $this->currentStageId = $record->current_stage_id ?? null; // ✅ Get current stage
    }

    #[On('refreshSalesStageStepper')]// ✅ Listen only for this record's updates
public function updateStage()
{
    $this->record->refresh(); // ✅ Fetch latest data from DB
    $this->currentStageId = $this->record->current_stage_id;
}

    public function render()
    {
        return view('livewire.sales-stage-stepper', [
            'salesStages' => $this->salesStages,
            'currentStageId' => $this->currentStageId,
        ]);
    }
}
