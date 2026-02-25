<?php

namespace App\Models;

use App\Enums\ContractStatus;
use App\Models\Traits\Companies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use Companies;

    protected $fillable = [
        'unit_name',
        'customer_name',
        'rent_amount',
        'start_date',
        'end_date',
        'status',
    ]; 
        
    protected $casts = [
        'status' => ContractStatus::class,
    ];
        
    public function invoices() : HasMany{
        return $this->hasMany(Invoice::class);
    }
}
