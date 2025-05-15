<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use App\Models\SalesInteractionFollowup;

class FollowupsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return SalesInteractionFollowup::where('status', '!=', 'completed');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('followup_date')
                ->label('Follow-Up Date')
                ->sortable(),

            Tables\Columns\TextColumn::make('note')
                ->label('Notes')
                ->limit(50),

        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('complete')
                ->label('Mark as Completed')
                ->color('success')
                ->icon('heroicon-o-check')
                ->action(fn (SalesInteractionFollowup $record) => $record->update(['status' => 'completed']))
                ->visible(fn (SalesInteractionFollowup $record) => $record->status !== 'completed')
                ->requiresConfirmation(),
        ];
    }
}
