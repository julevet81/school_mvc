<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Fee extends Model { protected $fillable=['school_id','branch_id','code','name','amount','frequency','is_active']; protected function casts(): array { return ['is_active'=>'boolean']; } }
