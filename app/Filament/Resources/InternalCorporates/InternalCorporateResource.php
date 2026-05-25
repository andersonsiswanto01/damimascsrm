<?php

namespace App\Filament\Resources\InternalCorporates;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\InternalCorporates\Pages\ListInternalCorporates;
use App\Filament\Resources\InternalCorporates\Pages;
use App\Filament\Resources\InternalCorporateResource\RelationManagers;
use App\Models\InternalCorporate;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternalCorporateResource extends Resource
{
    protected static ?string $model = InternalCorporate::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-m-briefcase';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('psm')
                    ->required()
                    ->maxLength(255),
                TextInput::make('province_id')
                    ->required()
                    ->numeric(),
                TextInput::make('internal_corporate_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('initial')
                    ->required()
                    ->maxLength(10),
                TextInput::make('legal_form')
                    ->required()
                    ->maxLength(255),
                TextInput::make('pt_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    
    public static function table(Table $table): Table
    {
       return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('psm')->sortable()->searchable(),
                TextColumn::make('legal_form')->label('Legal Form'),
                TextColumn::make('initial')->sortable()->searchable(),
                TextColumn::make('pt_name')->label('Corporate Name')->sortable()->searchable(),
                TextColumn::make('name')->label('Estate')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([
            ])
            ->toolbarActions([
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
            'index' => ListInternalCorporates::route('/'),
        ];
    }
}
