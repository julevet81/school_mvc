<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class IntegrationConnection extends Model { protected $fillable=['school_id','branch_id','integration_provider_id','name','credentials_encrypted','settings','status','last_synced_at']; protected function casts(): array { return ['credentials_encrypted'=>'array','settings'=>'array','last_synced_at'=>'datetime']; } }
