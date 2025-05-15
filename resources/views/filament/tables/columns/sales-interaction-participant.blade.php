<div>
    @php
    $types = collect($getState())->unique()->values();
@endphp

<x-filament::badge
    :color="match (true) {
        $types->contains('App\Models\Customer') && $types->contains('App\Models\LeadProspect') => 'info',
        $types->contains('App\Models\Customer') => 'success',
        $types->contains('App\Models\LeadProspect') => 'warning',
        default => 'gray'
    }"
>
    {{ match (true) {
        $types->contains('App\Models\Customer') && $types->contains('App\Models\LeadProspect') => 'Customer & Lead Prospect',
        $types->contains('App\Models\Customer') => 'Customer',
        $types->contains('App\Models\LeadProspect') => 'Lead Prospect',
        default => 'Unknown'
    } }}
</x-filament::badge>


</div>
