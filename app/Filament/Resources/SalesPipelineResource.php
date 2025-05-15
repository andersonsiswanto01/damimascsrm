<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesPipelineResource\Pages;
use App\Filament\Resources\SalesPipelineResource\RelationManagers;
use App\Models\SalesPipeline;
use App\Models\SalesStage;
use Filament\Forms;
use Filament\Forms\Form;
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

    protected static ?string $navigationGroup = 'Sales Management';
    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('lead_id')
                ->relationship('lead', 'name')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('status')
                ->options(SalesPipeline::STATUSES)
                ->required()
                ->default('new'),

            Forms\Components\Select::make('assigned_to')
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
            Tables\Columns\TextColumn::make('lead.name')
                ->label('Lead')
                ->sortable()
                ->searchable(), // Display Lead Name

                Tables\Columns\TextColumn::make('customer.customer_name')
                ->label('Customer')
                ->sortable()
                ->searchable()
                ->toggleable(), // Customer
                
            
            Tables\Columns\TextColumn::make('status')
                ->sortable()
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'open' => 'gray',
                    'in_progress' => 'yellow',
                    'won' => 'green',
                    'lost' => 'red',
                }), // Status with badge color
            
            Tables\Columns\TextColumn::make('assignedUser.name')
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
            

            
            Tables\Columns\TextColumn::make('deal_value')
                ->label('Deal Value')
                ->sortable()
                ->money('IDR'), // Numeric value formatted as money
            
            Tables\Columns\TextColumn::make('expected_close')
                ->label('Expected Close')
                ->sortable()
                ->date(), // Expected close date
            
            Tables\Columns\TextColumn::make('created_at')
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
        ->actions([
            Tables\Actions\EditAction::make(), // Edit button
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(), // Bulk delete
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
            'index' => Pages\ListSalesPipelines::route('/'),
            'create' => Pages\CreateSalesPipeline::route('/create'),
            'edit' => Pages\EditSalesPipeline::route('/{record}/edit'),
        ];
    }
}
