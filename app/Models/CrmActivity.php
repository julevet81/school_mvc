<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CrmActivity extends Model { protected $table='crm_activities'; protected $fillable=['crm_lead_id','type','activity_at','notes','created_by']; protected function casts(): array { return ['activity_at'=>'datetime']; } }
