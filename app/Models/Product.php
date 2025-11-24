<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Game;
use App\Models\Category;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'available',
        'game_id',
        'category_id',
    ];
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}