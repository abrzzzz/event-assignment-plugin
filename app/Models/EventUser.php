<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// This model should be removed
class EventUser extends Model
{
    use HasFactory;

    /**
     * $table
     *
     * @var string
     */
    protected $table = "event_user_attendances";

    /**
     * $fillable
     *
     * @var array
     */
    public $fillable = [
        'event_id',
        'email',
    ];


    
}