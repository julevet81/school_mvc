<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Payment extends Model { protected $fillable=['school_id','branch_id','invoice_id','payment_no','amount','method','status','gateway_reference','received_by','paid_at']; protected function casts(): array { return ['paid_at'=>'datetime']; } public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class);} }
