<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    protected $table = 'workspaces';

    protected $fillable = [
        'username',
        'board',
        'member',
    ];

    protected $casts = [
        'member' => 'array',
    ];

    public function boards()
    {
        return $this->hasMany(Board::class, 'workspace_id');
    }
}
