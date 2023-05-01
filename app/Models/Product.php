<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
  
class Product extends Model
{
    use HasFactory;
  
    protected $fillable = [
        'name', 'detail', 'image','price','barcode','quantity', 'market_id'
    ];


    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

}