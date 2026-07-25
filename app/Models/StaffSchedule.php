<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StaffSchedule extends Model
{
    protected $fillable=['user_id','shift_date','starts_at','ends_at','area','notes'];
    protected function casts(): array { return ['shift_date'=>'date']; }
    public function user(){return $this->belongsTo(User::class);}
}
