<?php
namespace App\Http\Controllers;

use App\Models\RestaurantTable;
use App\Models\StaffAttendance;
use App\Models\StaffSchedule;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function storeSchedule(Request $request)
    {
        $this->ensureAdmin();
        StaffSchedule::create($request->validate(['user_id'=>['required','exists:users,id'],'shift_date'=>['required','date'],'starts_at'=>['required','date_format:H:i'],'ends_at'=>['required','date_format:H:i','after:starts_at'],'area'=>['nullable','string','max:60'],'notes'=>['nullable','string','max:300']]));
        return back()->with('success','Shift scheduled.');
    }
    public function destroySchedule(StaffSchedule $schedule){$this->ensureAdmin();$schedule->delete();return back()->with('success','Shift removed.');}

    public function assign(Request $request, User $user)
    {
        $this->ensureAdmin();
        $validated=$request->validate(['assigned_area'=>['nullable','string','max:60'],'table_ids'=>['nullable','array'],'table_ids.*'=>['integer','exists:restaurant_tables,id']]);
        $user->update(['assigned_area'=>$validated['assigned_area']??null]);
        RestaurantTable::where('assigned_user_id',$user->id)->update(['assigned_user_id'=>null]);
        if(!empty($validated['table_ids'])) RestaurantTable::whereIn('id',$validated['table_ids'])->update(['assigned_user_id'=>$user->id]);
        return back()->with('success','Service assignment updated.');
    }

    public function clockIn(){ $user=auth()->user(); if(StaffAttendance::where('user_id',$user->id)->whereNull('clocked_out_at')->exists()) return back()->with('error','You are already clocked in.'); StaffAttendance::create(['user_id'=>$user->id,'clocked_in_at'=>now()]); return back()->with('success','Clocked in.'); }
    public function startBreak(){ $attendance=$this->openAttendance(); if($attendance->break_started_at)return back()->with('error','Break already started.');$attendance->update(['break_started_at'=>now()]);return back()->with('success','Break started.'); }
    public function endBreak(){ $attendance=$this->openAttendance(); if(!$attendance->break_started_at)return back()->with('error','No active break.');$minutes=max(1,$attendance->break_started_at->diffInMinutes(now()));$attendance->update(['break_minutes'=>$attendance->break_minutes+$minutes,'break_started_at'=>null]);return back()->with('success','Break ended.'); }
    public function clockOut(){ $attendance=$this->openAttendance(); if($attendance->break_started_at){$attendance->break_minutes+=max(1,$attendance->break_started_at->diffInMinutes(now()));$attendance->break_started_at=null;}$attendance->clocked_out_at=now();$attendance->save();return back()->with('success','Clocked out.'); }
    private function openAttendance(): StaffAttendance{return StaffAttendance::where('user_id',auth()->id())->whereNull('clocked_out_at')->latest()->firstOrFail();}
    private function ensureAdmin():void{abort_unless(auth()->user()?->isAdmin(),403);}
}
