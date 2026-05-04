<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MarketingCampaign extends Model { protected $fillable=['school_id','branch_id','name','channel','starts_on','ends_on','budget','status']; protected function casts(): array { return ['starts_on'=>'date','ends_on'=>'date']; } }
