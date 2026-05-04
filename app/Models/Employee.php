<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Employee extends Model { protected $fillable=['school_id','branch_id','user_id','department_id','employee_no','job_title','hire_date','employment_type','status']; protected function casts(): array { return ['hire_date'=>'date']; } }
