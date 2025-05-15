<?php

namespace App\Livewire;

use Livewire\Component;

class MySalesPipeline extends Component
{

    public $record; // To store the current sales pipeline record

    public function mount($recordId = null)
    {

        $salesPipeline = SalesPipeline::with('lead')->find(1); // Replace '1' with actual ID

        if ($salesPipeline && $salesPipeline->lead) {
            $leadName = $salesPipeline->lead->name;
            $leadPhone = $salesPipeline->lead->phone;
        } else {
            $leadName = 'No Name';
            $leadPhone = 'No Phone';
        }


        if ($recordId) {
            $this->record = SalesPipeline::find($recordId);
        }
    }

    public function render()
    {
        return view('livewire.my-sales-pipeline');
    }
}
