<?php

namespace App\Filament\Resources\CorporateSp2bks\CorporateSp2bks;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CorporateSp2bks\Pages\ListCorporateSp2bks;
use App\Filament\Resources\CorporateSp2bks\Pages;
use App\Filament\Resources\CorporateSp2bksResource\RelationManagers;
use App\Models\CorporateSp2bks;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\CorporateSp2bk;

class CorporateSp2bksResource extends Resource
{
    protected static ?string $model = CorporateSp2bk::class;
    protected static string | \UnitEnum | null $navigationGroup = 'Corporate Analyzer'; // This groups it under 'Corporate Resources'
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer.customer_name')
                    ->label('Customer Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer.company_name')
                    ->label('Corporate Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('expiry_date')
                    ->label('Expiry Date')
                    ->sortable()
                    ->searchable(),
                    
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->schema([
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

                EditAction::make()
                ->schema([
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
            'index' => ListCorporateSp2bks::route('/'),
        ];
    }
}
