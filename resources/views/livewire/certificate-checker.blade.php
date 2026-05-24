
<div>
    <body class="d-flex flex-column min-vh-100">
<main class="container-xl px-0 flex-grow-1">
  
  <section class="bg-white pt-4 pb-5 pt-md-5 pb-md-5">

    
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-body">

                    <h4 class="text-center mb-4">Certificate Checker</h4>

                    <div class="mb-3">
                        <label class="form-label">Insert Certificate ID</label>
                       <input type="text"
                                wire:model="certificateId"
                                wire:keydown.enter="check"
                                class="form-control"
                                placeholder="Insert Certificate ID">
                        <label class="form-label">Select Province</label>
                        <select wire:model="selectedProvince" class="form-select">
                            <option value="">-- Select Province --</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                        @error('certificateId')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                     <button wire:click="check"
                            wire:loading.attr="disabled"
                            class="btn btn-danger w-100">
                        Check Certificate
                    </button>

                </div>
                        @if(session()->has('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($certificate)
                        <div class="card mt-4 p-3 bg-light">
                            <h5 class="mb-3">Certificate Details</h5>
                            <p><strong>Name:</strong> {{ $certificate->name }}</p>
                            <p><strong>Product Name:</strong> {{ $certificate->product }}</p>
                            <p><strong>Date:</strong> {{ $certificate->date }}</p>
                            <p><strong>Province:</strong> {{ $certificate->province->name }}</p>
                            <p><strong>City:</strong> {{ $certificate->city }}</p>
                        </div>
                    @endif
            </div>

        </div>
        
    </div>

</main>
{{-- Result Section --}}
            

</div>
