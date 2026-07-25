<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StaffAttendance extends Model
{
    protected $fillable=['user_id','clocked_in_at','clocked_out_at','break_started_at','break_minutes','notes'];
    protected function casts(): array{return ['clocked_in_at'=>'datetime','clocked_out_at'=>'datetime','break_started_at'=>'datetime','break_minutes'=>'integer'];}
    public function user(){return $this->belongsTo(User::class);}
}
