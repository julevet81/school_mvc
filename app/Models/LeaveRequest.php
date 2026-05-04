<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LeaveRequest extends Model { protected $fillable=['employee_id','leave_type','start_date','end_date','days','status','approved_by','reason']; protected function casts(): array { return ['start_date'=>'date','end_date'=>'date']; } }
