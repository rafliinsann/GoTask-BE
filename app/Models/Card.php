<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = [
        'cover',
        'title',
        'assign',
        'label',
        'deadline',
        'deskripsi',
	    'board_id',
        'colour'
];


    protected $casts = [
        'label' => 'array',
        'assign' => 'array',
    ];

    public function board()
    {
    	return $this->belongsTo(Board::class, 'board_id');
    }
}

