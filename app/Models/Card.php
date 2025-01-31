<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = [
        'list_id',
        'cover',
        'title',
        'assign',
        'label',
        'deadline',
        'deskripsi'];


    protected $casts = [
        'label' => 'array',
    ];
    public function list()
    {
        return $this->belongsTo(Listt::class, 'list_id');
    }
}

