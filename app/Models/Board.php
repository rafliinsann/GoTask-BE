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
        'list',
        'member',
    ];

    protected $casts = [
        'list' => 'array',
        'member' => 'array',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function lists()
    {
        return $this->hasMany(Listt::class, 'board_id');
    }
}
