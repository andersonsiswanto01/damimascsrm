<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InternalCorporateResource\Pages;
use App\Filament\Resources\InternalCorporateResource\RelationManagers;
use App\Models\InternalCorporate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternalCorporateResource extends Resource
{
    protected static ?string $model = InternalCorporate::class;

    protected static ?string $navigationIcon = 'heroicon-m-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('psm')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('province_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('internal_corporate_type_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('initial')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('legal_form')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pt_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    
    public static function table(Table $table): Table
    {
       return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('psm')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('legal_form')->label('Legal Form'),
                Tables\Columns\TextColumn::make('initial')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('pt_name')->label('Corporate Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Estate')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('id', 'desc')
            ->actions([
            ])
            ->bulkActions([
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
            'index' => Pages\ListInternalCorporates::route('/'),
        ];
    }
}
