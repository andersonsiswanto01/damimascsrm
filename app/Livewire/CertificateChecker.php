<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TemporaryCertificate;
use App\Models\Province;

class CertificateChecker extends Component
{

    public $certificateId = '';
    public $certificate = null;
    public $province_id = null;
    public $notFound = false;
    public $provinces = [];
    public $selectedProvince;

public function mount()
    {
         $this->provinces = Province::orderBy('name')->get();
    }

 public function check()
    {
           $this->validate([
        'certificateId' => 'required|string',
        'selectedProvince' => 'required|integer',
            ]);


        // Find certificate by ID
        $this->certificate = TemporaryCertificate::where('id', $this->certificateId)->first();
        $this->province_id = Province::where('id', $this->selectedProvince)->first();

        if (!$this->certificate) {
            session()->flash('error', 'Certificate not found!');
            return;
        }

        if ($this->certificate->province_id != $this->selectedProvince) {
        session()->flash('error', 'Certificate does not belong to the selected province!');
        $this->certificate = null; // clear certificate
        return;
        }
    }

        protected function rules(): array
    {
        return [
            'certificateId' => 'required|string',
        ];
    }


    public function resetForm()
    {
        $this->reset(['certificateId', 'certificate', 'notFound']);
    }

    
    public function render()
    {
            return view('livewire.certificate-checker');

    }
}
