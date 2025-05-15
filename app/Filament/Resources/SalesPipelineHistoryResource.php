<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesPipelineHistoryResource\Pages;
use App\Filament\Resources\SalesPipelineHistoryResource\RelationManagers;
use App\Models\SalesPipelineHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesPipelineHistoryResource extends Resource
{
    protected static ?string $model = SalesPipelineHistory::class;

    protected static ?string $navigationGroup = 'Sales Management';
    
    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSalesPipelineHistories::route('/'),
            'create' => Pages\CreateSalesPipelineHistory::route('/create'),
            'edit' => Pages\EditSalesPipelineHistory::route('/{record}/edit'),
        ];
    }
}
