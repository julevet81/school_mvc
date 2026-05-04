<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Contract extends Model { protected $fillable=['employee_id','contract_type','start_date','end_date','base_salary','terms','status']; protected function casts(): array { return ['start_date'=>'date','end_date'=>'date','terms'=>'array']; } }
