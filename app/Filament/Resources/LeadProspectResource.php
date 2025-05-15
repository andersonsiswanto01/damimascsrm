<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadProspectResource\Pages;
use App\Filament\Resources\LeadProspectResource\RelationManagers;
use App\Models\LeadProspect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;


class LeadProspectResource extends Resource
{
    protected static ?string $model = LeadProspect::class;

    
    protected static ?string $navigationGroup = 'Customer Lead Management';
    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('phone')
                ->required()
                ->unique(table: LeadProspect::class, ignorable: fn ($record) => $record)
                ->tel(),

            TextInput::make('email')
                ->email()
                ->maxLength(255)
                ->nullable(),

            Select::make('event_id')
                ->relationship('event', 'name')
                ->searchable()
                ->nullable()
                ->label('Event Source'),

            Select::make('status')
                ->options([
                    'new' => 'New',
                    'contacted' => 'Contacted',
                    'negotiation' => 'Negotiation',
                    'converted' => 'Converted',
                    'lost' => 'Lost',
                ])
                ->default('new')
                ->required(),

            DatePicker::make('created_at')
                ->label('Date Created')
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('name')
                ->searchable()
                ->sortable(),

            TextColumn::make('phone')
                ->searchable(),

            TextColumn::make('email')
                ->searchable()
                ->sortable(),

            TextColumn::make('event.name')
                ->label('Event Source')
                ->sortable(),

            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'new' => 'gray',
                    'contacted' => 'warning',
                    'negotiation' => 'info',
                    'converted' => 'success',
                    'lost' => 'danger',
                })
                ->sortable(),

            TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('status')
                ->options([
                    'new' => 'New',
                    'contacted' => 'Contacted',
                    'negotiation' => 'Negotiation',
                    'converted' => 'Converted',
                    'lost' => 'Lost',
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLeadProspects::route('/'),
            'create' => Pages\CreateLeadProspect::route('/create'),
            'edit' => Pages\EditLeadProspect::route('/{record}/edit'),
        ];
    }
}
