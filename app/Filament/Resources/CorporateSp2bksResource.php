<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CorporateSp2bksResource\Pages;
use App\Filament\Resources\CorporateSp2bksResource\RelationManagers;
use App\Models\CorporateSp2bks;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CorporateSp2bksResource extends Resource
{
    protected static ?string $model = CorporateSp2bks::class;
    protected static ?string $navigationGroup = 'Corporate Analyzer'; // This groups it under 'Corporate Resources'
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.customer_name')
                    ->label('Customer Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.company_name')
                    ->label('Corporate Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Expiry Date')
                    ->sortable()
                    ->searchable(),
                    
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form([
                        TextInput::make('id')
                                    ->label('SP2BKS ID')
                                    ->required(),

                        FileUpload::make('sp2bks_file')
                                    ->label('SP2BKS File(s)')
                                    ->multiple()
                                    ->downloadable()
                                    ->disk('local')
                                    ->openable()
                                    ->directory('private/documents')
                                    ->visibility('private')
                                    ->fetchFileInformation(false)
                                    ->preserveFilenames()
                                    ->nullable()
                                    ->deletable(false),
                        
                        Repeater::make('sp2bksProducts')
                        ->label('Products in SP2BKS')
                        ->relationship('sp2bksProducts')
                        ->schema([
                            Select::make('produsen_benih_product_id')
                                ->label('Product')
                                ->relationship('produsenBenihProduct', 'product_name')
                                ->searchable()
                                ->preload()
                                ->getOptionLabelFromRecordUsing(function ($record) {
                                    return optional($record->produsen)->name . ' - ' . $record->product_name;
                                }),

                                TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()
                                ->required(),

                                ])
                                ->collapsible()
                                ->defaultItems(1)
                                ->columns(2),
                            ]),

                Tables\Actions\EditAction::make()
                ->form([
                    TextInput::make('id')
                                ->label('SP2BKS ID')
                                ->required(),

                    FileUpload::make('sp2bks_file')
                                ->label('SP2BKS File(s)')
                                ->multiple()
                                ->downloadable()
                                ->disk('local')
                                ->openable()
                                ->directory('private/documents')
                                ->visibility('private')
                                ->fetchFileInformation(false)
                                ->preserveFilenames()
                                ->nullable()
                                ->deletable(false),
                    
                    Repeater::make('sp2bksProducts')
                    ->label('Products in SP2BKS')
                    ->relationship('sp2bksProducts')
                    ->schema([
                        Select::make('produsen_benih_product_id')
                            ->label('Product')
                            ->relationship('produsenBenihProduct', 'product_name')
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                return optional($record->produsen)->name . ' - ' . $record->product_name;
                            }),

                            TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required(),

                            ])
                            ->collapsible()
                            ->defaultItems(1)
                            ->columns(2),
                        ]),
                        
                
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
            'index' => Pages\ListCorporateSp2bks::route('/'),
        ];
    }
}
