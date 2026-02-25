<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Models\Traits\Companies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use Companies;
    
    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_method',
        'reference_number',
        'paid_at',
    ];

    protected $casts = [
        'payment_method' => PaymentMethod::class,
    ];

    public function invoice() : BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
