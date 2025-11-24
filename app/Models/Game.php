<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'available',
        'game_id',
        'category_id',
    ];

    protected $casts = [
        'available' => 'boolean',
        'price' => 'integer',
        'category_id' => 'integer',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = ['status_text'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Attributes
    |--------------------------------------------------------------------------
    */

    public function getStatusTextAttribute()
    {
        return $this->available ? 'Available' : 'Unavailable';
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    // only get available games
    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

    // filter by category
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // search
    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'LIKE', "%{$keyword}%");
    }
}
