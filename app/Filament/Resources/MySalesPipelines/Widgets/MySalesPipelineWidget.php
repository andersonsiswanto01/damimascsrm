<?php

namespace App\Filament\Resources\MySalesPipelines\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Filament\Widgets\Widget;
use App\Models\SalesPipeline;
use App\Models\LeadProspect;
use App\Models\SalesPipelineHistory;
use App\Models\User;
use App\Models\Customer;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MySalesPipelineWidget extends BaseWidget
{
    public function table(Table $table): Table
    {

        dd('test');
        return $table
            ->query(SalesPipeline::where('assigned_to', Auth::id()))
            ->columns([
                TextColumn::make('customer.customer_name')->label('Customer')->sortable(),
                TextColumn::make('status')->sortable(),
                TextColumn::make('deal_value')->label('Expected Value')->money('IDR'),
            ])
            ->recordActions([
                Action::make('View Details')
                    ->url(fn (SalesPipeline $record) => route('filament.resources.sales-pipeline.edit', ['record' => $record->id]))
                    ->icon('heroicon-o-eye')
                    ->button(),
            ]);
    }
}
