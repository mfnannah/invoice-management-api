<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Models\Traits\Companies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use Companies;
    
    protected $fillable = [
        'contract_id',
        'invoice_number',
        'subtotal',
        'tax_amount',
        'total',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
    ];

    public function contract() : BelongsTo {
        return $this->belongsTo(Contract::class);
    }
    
    public function payments() : HasMany{
        return $this->hasMany(Payment::class);
    }
}
