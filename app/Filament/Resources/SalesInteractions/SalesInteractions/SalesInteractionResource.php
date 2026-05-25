<?php

namespace App\Filament\Resources\SalesInteractions\SalesInteractions;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SalesInteractions\Pages\ListSalesInteractions;
use App\Filament\Resources\SalesInteractions\Pages\CreateSalesInteraction;
use App\Filament\Resources\SalesInteractions\Pages\EditSalesInteraction;
use App\Filament\Resources\SalesInteractions\Pages;
use App\Filament\Resources\SalesInteractionResource\RelationManagers;
use App\Models\SalesInteraction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Columns\TextareaColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Hidden;
use App\Models\Customer;
use App\Models\LeadProspect;
use App\Models\SalesInteractionParticipant;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\ViewColumn;
use App\Models\SalesPipeline;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Components\TextInput;
use App\Actions\ResetStars;

class SalesInteractionResource extends Resource
{
    protected static ?string $model = SalesInteraction::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
{
    return $schema->components([

        Section::make('Interaction Details')->schema([
            Select::make('sales_pipeline_id')
                ->label('Sales Pipeline')
                ->options(function () {
                    return SalesPipeline::with(['lead', 'customer'])
            ->where('assigned_to', Auth::id()) // 👈 filter by current user
            ->where(function ($query) {
                $query->whereNotNull('lead_id')
                      ->orWhereNotNull('customer_id');
            })
            ->get()
            ->mapWithKeys(function ($pipeline) {
                $label = 'Pipeline #' . $pipeline->id;

                if ($pipeline->lead) {
                    $label .= ' - Lead: ' . $pipeline->lead->name;
                } elseif ($pipeline->customer) {
                    $label .= ' - Customer: ' . $pipeline->customer->name;
                }

                return [$pipeline->id => $label];
            });
                })
                ->nullable()
                ->preload(),

            Select::make('interaction_type')
                ->label('Interaction Type')
                ->options([
                    'call' => 'Call',
                    'meeting' => 'Meeting',
                    'email' => 'Email',
                    'whatsapp' => 'WhatsApp',
                    'sms' => 'SMS',
                    'other' => 'Other',
                ])
                ->required(),

            Textarea::make('summary')
                ->label('Summary')
                ->rows(4)
                ->required(),

            DatePicker::make('interaction_date')
                ->label('Interaction Date')
                ->required(),

        ]),

        Section::make('Follow Ups')->schema([
            Repeater::make('followups_date') // Relates to hasMany('followups')
                ->label('Follow-Up Dates')
                ->relationship('followups') // This connects it to the SalesInteractionFollowup model
                ->schema([
                    DatePicker::make('followup_date')
                        ->label('Follow-Up Date'),

                        Textarea::make('note')
                        ->label('Notes')
                        ->nullable()
                        ->rows(3),

                ])
                ->collapsible()
                ->addActionLabel('Add Follow-Up Date')
                ->minItems(0)
                ->maxItems(1),
            
                ]),

        
           
        Section::make('Participants')->schema([
            Repeater::make('participants')
                ->label('Participants')
                ->relationship('participants') // Define the relation to `SalesInteractionParticipant`
                ->schema([
                    MorphToSelect::make('participant')
                        ->types([
                            Type::make(Customer::class)
                                ->titleAttribute('customer_name'),
                            Type::make(LeadProspect::class)
                                ->titleAttribute('name'),
                        ])
                        ->searchable()
                        ->preload()
                        ->required(),
                ])
                ->createItemButtonLabel('Add Participant') // Custom button label
                ->collapsible(), // Makes the UI cleaner,

        ]),
    
        Hidden::make('created_by')
            ->default(auth()->id()),
    ]);
}


    public static function table(Table $table): Table
    {
        return $table
        ->columns([

            TextColumn::make('participants')
            ->label('Participants')
            ->sortable()
            ->searchable()
            ->formatStateUsing(fn ($record) => 
                $record->participants
                    ->unique('participant_id') // Ensures each participant is only shown once
                    ->map(fn ($participant) => match ($participant->participant_type) {
                        'App\Models\Customer' => $participant->participant?->customer_name,
                        'App\Models\LeadProspect' => $participant->participant?->name,
                        default => 'Unknown'
                    })
                    ->filter()
                    ->join(', ')
            ),
        
        // TextColumn::make('participants.participant_type')
        //     ->label('Participant Type')
        //     ->sortable()
        //     ->badge()
        //     ->colors([
        //         'Customer' => 'success', // Green for Customers
        //         'Lead Prospect' => 'warning', // Yellow for Lead Customers
        //         'Customer & Lead Prospect' => 'purple', // Purple if both exist
        //         'Unknown' => 'gray' // Gray for unknowns
        //     ])
        //     ->formatStateUsing(function ($state, $record) {
        //         $types = collect($record->participants)
        //             ->map(fn ($participant) => match ($participant->participant_type) {
        //                 'App\Models\Customer' => 'Customer',
        //                 'App\Models\LeadProspect' => 'Lead Prospect',
        //                 default => 'Unknown'
        //             })
        //             ->unique()
        //             ->values();
        
        //         if ($types->contains('Customer') && $types->contains('Lead Prospect')) {
        //             return 'Customer & Lead Prospect';
        //         } elseif ($types->isNotEmpty()) {
        //             return $types->first();
        //         }
                
        //         return 'Unknown';
        //     }),
        
        ViewColumn::make('participants.participant_type')
        ->label('Participant Type')
        ->view('filament.tables.columns.sales-interaction-participant'),
        
            // Sales Pipeline Column
            TextColumn::make('salesPipeline.lead.id') // Ensure correct relation
            ->label('Sales Pipeline')
            ->sortable()
            ->searchable(),
                

                    
            // Interaction Type
            TextColumn::make('interaction_type')
                ->label('Type')
                ->sortable(),
        
            // Interaction Date
            TextColumn::make('interaction_date')
                ->label('Date')
                ->date()
                ->sortable(),
        
            // Summary
            TextColumn::make('summary')
                ->label('Summary')
                ->limit(50),
        
            // Created By
            TextColumn::make('createdBy.name')
                ->label('Created By')
                ->sortable(),
        ])        
        ->filters([])
        ->recordActions([
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->toolbarActions([
            DeleteBulkAction::make(),
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
            'index' => ListSalesInteractions::route('/'),
            'create' => CreateSalesInteraction::route('/create'),
            'edit' => EditSalesInteraction::route('/{record}/edit'),
        ];
    }
}
