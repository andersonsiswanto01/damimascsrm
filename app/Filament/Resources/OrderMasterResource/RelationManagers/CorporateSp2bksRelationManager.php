<?php

namespace App\Filament\Resources\OrderMasterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')->label('SP2BKS ID')->required(),
                Forms\Components\FileUpload::make('sp2bks_file')
                    ->disk('local')
                    ->directory('private/corporate_sp2bks')
                    ->downloadable(),
                Forms\Components\DatePicker::make('expiry_date')->label('Expiry Date'),
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
                Tables\Columns\TextColumn::make('id')
                    ->label('SP2BKS ID'),

                Tables\Columns\TextColumn::make('expiry_date')
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
            ->actions([])
            ->bulkActions([]);
    }
}
