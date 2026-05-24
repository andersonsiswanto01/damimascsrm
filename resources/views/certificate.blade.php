@include('header')


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
                               class="form-control">
                        @error('certificateId')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button wire:click="check"
                            class="btn btn-primary w-100 btn-danger">
                        Check Certificate
                    </button>

                </div>
            </div>

        </div>
    </div>

</main>
@include('footer')


