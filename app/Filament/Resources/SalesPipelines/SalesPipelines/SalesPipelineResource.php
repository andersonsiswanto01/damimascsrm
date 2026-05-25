<?php

namespace App\Filament\Resources\SalesPipelines\SalesPipelines;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SalesPipelines\Pages\ListSalesPipelines;
use App\Filament\Resources\SalesPipelines\Pages\CreateSalesPipeline;
use App\Filament\Resources\SalesPipelines\Pages\EditSalesPipeline;
use App\Filament\Resources\SalesPipelines\Pages;
use App\Filament\Resources\SalesPipelineResource\RelationManagers;
use App\Models\SalesPipeline;
use App\Models\SalesStage;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\CreateRecord;
use Filament\Tables\Columns\BadgeColumn;


class SalesPipelineResource extends Resource
{
    protected static ?string $model = SalesPipeline::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Sales Management';
    
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            Select::make('lead_id')
                ->relationship('lead', 'name')
                ->searchable()
                ->required(),
            Select::make('status')
                ->options(SalesPipeline::STATUSES)
                ->required()
                ->default('new'),

            Select::make('assigned_to')
                ->relationship('assignedUser', 'name')
                ->required(),

            Select::make('current_stage_id')
                ->relationship('salesStage', 'name') // Fetch from related model
                ->label('Sales Stage')
                ->searchable()
                ->preload() // Ensures all options are listed initially
                ->required(),
            
            Select::make('customer_id')
                ->relationship('customer', 'customer_name')
                ->searchable(),

            Textarea::make('notes')
                ->columnSpanFull(),

            DatePicker::make('expected_close')
                ->required(),

            TextInput::make('deal_value')
                ->numeric()
                ->required(),
            
        ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('lead.name')
                ->label('Lead')
                ->sortable()
                ->searchable(), // Display Lead Name

                TextColumn::make('customer.customer_name')
                ->label('Customer')
                ->sortable()
                ->searchable()
                ->toggleable(), // Customer
                
            
            TextColumn::make('status')
                ->sortable()
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'open' => 'gray',
                    'in_progress' => 'yellow',
                    'won' => 'green',
                    'lost' => 'red',
                }), // Status with badge color
            
            TextColumn::make('assignedUser.name')
                ->label('Assigned To')
                ->sortable()
                ->searchable(), // Assigned User
            
                TextColumn::make('salesStage.name')
                ->label('Sales Stage')
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'Prospecting' => 'gray',
                    'Contacted' => 'info',
                    'Qualification' => 'info',
                    'Proposal Sent' => 'warning',
                    'Negotiation' => 'primary',
                    'Closed Won' => 'success',
                    'Closed Lost' => 'danger',
                    default => 'gray', // Fallback color
                })
                ->sortable(),
            

            
            TextColumn::make('deal_value')
                ->label('Deal Value')
                ->sortable()
                ->money('IDR'), // Numeric value formatted as money
            
            TextColumn::make('expected_close')
                ->label('Expected Close')
                ->sortable()
                ->date(), // Expected close date
            
            TextColumn::make('created_at')
                ->label('Created At')
                ->sortable()
                ->dateTime(), // Creation timestamp
        ])
        ->filters([
            SelectFilter::make('status')
                ->options([
                    'new' => 'New',
                    'proposal' => 'Proposal',
                    'negotiation' => 'Negotiation',
                    'closed' => 'Closed',
                    'reassigned' => 'Reassigned',
                ]), // Dropdown filter for status
        ])
        ->recordActions([
            EditAction::make(), // Edit button
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(), // Bulk delete
            ]),
        ]);
}


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSalesPipelines::route('/'),
            'create' => CreateSalesPipeline::route('/create'),
            'edit' => EditSalesPipeline::route('/{record}/edit'),
        ];
    }
}
