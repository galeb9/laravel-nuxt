<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product', 'direction', 'price', 'quantity', 'date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
    ];

	public $timestamps = false;

    /*
     * Return transaction total amount
     */
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity * ($this->direction == "buy" ? -1 : 1);
    }

    /**
     * Scope a query to return transactions for specified product(s)
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string|array $product
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForProduct($query, $product)
    {
        return $query->whereIn('product', (array) $product);
    }
}
