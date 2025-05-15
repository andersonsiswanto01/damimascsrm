<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductQuantityHistory;

class Product extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // When a product is created, log initial stock
        static::created(function ($product) {
            ProductQuantityHistory::create([
                'product_id' => $product->id,
                'previous_quantity' => 0, // New product starts with 0 previous stock
                'quantity_change' => $product->stock, // Initial stock added
                'new_quantity' => $product->stock,
                'reason' => 'Initial stock on product creation',
                'user_id' => auth()->id(),
            ]);
        });
    }

    protected $fillable = [
        'id', 'name', 'price', 'stock', 'created_at', 'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function adjustStock(int $quantityChange, string $reason, int $userId)
    {
        // Get previous stock
        $previousQuantity = $this->stock;
        $newQuantity = $previousQuantity + $quantityChange;

        // Prevent negative stock
        if ($newQuantity < 0) {
            throw new \Exception('Stock cannot be negative.');
        }

        // Update product stock
        $this->update(['stock' => $newQuantity]);

        // Log the quantity change
        $this->logStockChange($previousQuantity, $quantityChange, $newQuantity, $reason, $userId);
    }

    private function logStockChange($previousQuantity, $quantityChange, $newQuantity, $reason, $userId)
    {
        ProductQuantityHistory::create([
            'product_id' => $this->id,
            'previous_quantity' => $previousQuantity,
            'quantity_change' => $quantityChange,
            'new_quantity' => $newQuantity,
            'reason' => $reason,
            'user_id' => $userId,
        ]);
    }
}
