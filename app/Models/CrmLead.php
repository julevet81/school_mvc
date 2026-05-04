<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CrmLead extends Model { protected $table='crm_leads'; protected $fillable=['school_id','branch_id','lead_no','full_name','email','phone','source','stage','score','owner_id','last_contact_at']; protected function casts(): array { return ['last_contact_at'=>'datetime']; } }
