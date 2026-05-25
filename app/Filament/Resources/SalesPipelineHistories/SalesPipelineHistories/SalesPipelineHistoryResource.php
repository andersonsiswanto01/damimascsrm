<?php

namespace App\Filament\Resources\SalesPipelineHistories\SalesPipelineHistories;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SalesPipelineHistories\Pages\ListSalesPipelineHistories;
use App\Filament\Resources\SalesPipelineHistories\Pages\CreateSalesPipelineHistory;
use App\Filament\Resources\SalesPipelineHistories\Pages\EditSalesPipelineHistory;
use App\Filament\Resources\SalesPipelineHistories\Pages;
use App\Filament\Resources\SalesPipelineHistoryResource\RelationManagers;
use App\Models\SalesPipelineHistory;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesPipelineHistoryResource extends Resource
{
    protected static ?string $model = SalesPipelineHistory::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Sales Management';
    
    protected static ?string $navigationParentItem ="My Sales Pipeline";
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListSalesPipelineHistories::route('/'),
            'create' => CreateSalesPipelineHistory::route('/create'),
            'edit' => EditSalesPipelineHistory::route('/{record}/edit'),
        ];
    }
}
