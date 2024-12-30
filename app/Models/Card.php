<?
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['cover', 'title', 'assign', 'label', 'deskripsi'];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
