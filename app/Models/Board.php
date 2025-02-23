<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $table = 'boards';

    protected $fillable = [
        'nama',
        'member',
        'user_id', // Tambahkan ini untuk mass assignment
        'workspace_id',
    ];

    protected $casts = [
        'member' => 'array',
    ];

    // Relasi ke Workspace
    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    // Relasi ke List
    public function cards()
    {
        return $this->hasMany(Card::class, 'board_id');
    }

    // Relasi ke User (pemilik board)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

