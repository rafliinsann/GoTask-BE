<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listt extends Model
{
    use HasFactory;

    protected $table = 'list';

    protected $fillable = [
        'card',
    ];

    protected $casts = [
        'card' => 'array',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }
    public function cards()
    {
        return $this->hasMany(Card::class, 'list_id');
    }
}
