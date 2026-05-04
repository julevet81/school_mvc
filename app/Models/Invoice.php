<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Invoice extends Model { protected $fillable=['school_id','branch_id','student_id','invoice_no','issue_date','due_date','subtotal','discount_total','penalty_total','total','paid_amount','status']; protected function casts(): array { return ['issue_date'=>'date','due_date'=>'date']; } public function school(): BelongsTo { return $this->belongsTo(School::class);} public function branch(): BelongsTo { return $this->belongsTo(Branch::class);} public function student(): BelongsTo { return $this->belongsTo(Student::class);} }
