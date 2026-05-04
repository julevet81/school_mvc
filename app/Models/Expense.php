<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Expense extends Model { protected $fillable=['school_id','branch_id','expense_category_id','expense_no','expense_date','amount','description','status','approved_by']; protected function casts(): array { return ['expense_date'=>'date']; } }
