<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesPipelineResource;
use App\Filament\Resources\MySalesPipelineResource\Pages;
use App\Models\SalesPipeline;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\View;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\{Repeater, Textarea, DateTimePicker, Select};
use Filament\Forms\Components\Button;
use Filament\Forms\Components\Actions;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;


class MySalesPipelineResource extends Resource
{
    protected static ?string $model = SalesPipeline::class;
    protected static ?string $navigationGroup = 'Sales Management';
    
    
    protected static ?string $navigationLabel = 'My Sales Pipeline';
    protected static ?string $slug = 'my-sales-pipeline';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * ✅ Filter sales pipelines to only show those assigned to the logged-in user.
     */


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('assigned_to', Auth::id());
    }

    /**
     * ✅ Define the form schema
     */

     
    public static function form(Form $form): Form
{
    return $form->schema([
        Grid::make(1) // 1 column layout
            ->schema([
                Placeholder::make('my_sales_pipeline')
                    ->label('') // Remove the default label
                    ->content(fn ($record) => view('livewire.my-sales-pipeline', ['record' => $record])),
                
                Placeholder::make('sales_stage_stepper')
                    ->content(fn ($record) => view('filament.forms.components.sales-stage-stepper', ['record' => $record]))
                    ->label(''), // Remove the default label

                    Repeater::make('interactions')
                    ->label('Chat Log')
                    ->relationship('interactions') // still needed to save data
                    ->default(function ($get) {
                        $pipelineId = $get('sales_pipeline_id');
    
                        if (! $pipelineId) return [];
    
                        return \App\Models\SalesInteraction::with('followup')
                            ->where('sales_pipeline_id', $pipelineId)
                            ->get()
                            ->map(function ($interaction) {
                                return [
                                    'interaction_type' => $interaction->interaction_type,
                                    'interaction_date' => $interaction->interaction_date,
                                    'summary' => $interaction->summary,
                                    'followup_date' => optional($interaction->followup)->followup_date,
                                ];
                            })
                            ->toArray();
                           
                    })
                    ->schema([
                        Select::make('interaction_type')
                            ->options([
                                'call' => 'Call',
                                'meeting' => 'Meeting',
                                'email' => 'Email',
                                'whatsapp' => 'WhatsApp',
                                'sms' => 'SMS',
                                'other' => 'Other',
                            ])
                            ->required()
                            ->label('Interaction Type'),
    
                        DateTimePicker::make('interaction_date')
                            ->label('Date & Time')
                            ->required(),
    
                        Textarea::make('summary')
                            ->label('Message/Notes')
                            ->rows(2)
                            ->placeholder('Type your interaction summary...'),
    
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
                    ])
                    ->addable()
                    ->deletable()
                    ->reorderable()
                    ->columnSpanFull(),

                // Proceed Button
                // ✅ Proceed Button
                Actions::make([
                    Actions\Action::make('proceed')
                        ->label('Proceed to Next Stage')
                        ->color('warning')
                        ->icon('heroicon-o-arrow-right')
                        ->action(function ($record, $livewire) { 
                            if ($record) {
                                $record->increment('current_stage_id'); // ✅ Increase stage by 1
                                $record->save();
                    
                                $livewire->dispatch('refreshSalesStageStepper'); // ✅ Refresh Livewire component
                                Notification::make()
                                    ->title('Sales Pipeline Updated')
                                    ->body('The sales pipeline has moved to the next stage.')
                                    ->success()
                                    ->send();
                            }
                        })
                        ->visible(fn ($livewire) => $livewire->record->current_stage_id < 4), // ✅ Check correct stage ID
                ]),
            ]),
    ]);
}
    
    

    /**
     * ✅ Use the existing SalesPipeline table but filter it for the assigned user
     */
    public static function table(Table $table): Table
    {
        return SalesPipelineResource::table($table)->modifyQueryUsing(
            fn (Builder $query) => $query->where('assigned_to', Auth::id())
        );
    }

    public static function getRelations(): array
    {
        return [];
    }

    /**
     * ✅ Define Filament resource pages
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMySalesPipelines::route('/'),
            'create' => Pages\CreateMySalesPipeline::route('/create'),
            'edit' => Pages\EditMySalesPipeline::route('/{record}/edit'),
        ];
    }
}
