<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JournalEntry extends Model { protected $fillable=['school_id','branch_id','entry_no','entry_date','reference_type','reference_id','memo']; protected function casts(): array { return ['entry_date'=>'date']; } }
