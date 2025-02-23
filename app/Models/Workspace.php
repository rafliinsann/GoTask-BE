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

    public function owner()
{
    return $this->belongsTo(User::class, 'owner_id');
}

public function members()
{
    return $this->belongsToMany(User::class, 'users', 'id', 'id')
                ->whereIn('id', $this->members ?? []);
}


    public function boards()
    {
        return $this->hasMany(Board::class, 'workspace_id');
    }
}
