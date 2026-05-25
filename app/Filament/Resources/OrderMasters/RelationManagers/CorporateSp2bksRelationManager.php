<?php

namespace App\Filament\Resources\OrderMasters\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CorporateSp2bksRelationManager extends RelationManager
{
    protected static string $relationship = 'corporateSp2bk';

    public function getTableQuery(): Builder
    {

        return $this->ownerRecord
            ->customer
            ->corporateSp2bk()
            ->getQuery();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')->label('SP2BKS ID')->required(),
                FileUpload::make('sp2bks_file')
                    ->disk('local')
                    ->directory('private/corporate_sp2bks')
                    ->downloadable(),
                DatePicker::make('expiry_date')->label('Expiry Date'),
                    ]);
    }

     public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        // Only show if the related customer's status is not "private"
        return $ownerRecord->customer && $ownerRecord->customer->status !== 'private';
    }
    
    public function table(Table $table): Table
    {
         return $table
            ->columns([
                TextColumn::make('id')
                    ->label('SP2BKS ID'),

                TextColumn::make('expiry_date')
                    ->label('Expiry Date')
                    ->date(),

                // Tables\Columns\TextColumn::make('sp2bks_file')
                //     ->label('File')
                //     ->formatStateUsing(fn ($state) =>
                //         $state
                //             ? '<a href="' . \Storage::url($state) . '" target="_blank">Download</a>'
                //             : '-'
                //     )
                //     ->html(),
            ])
            ->filters([])
            ->headerActions([]) // keep read-only; add create if you want editable
            ->recordActions([])
            ->toolbarActions([]);
    }
}
