<?
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'card', 'member', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
