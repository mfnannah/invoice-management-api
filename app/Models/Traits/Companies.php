<?php

namespace App\Models\Traits;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;

trait Companies
{
    protected static function bootCompanies(): void
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->tenant_id)) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });

        static::addGlobalScope('company', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where(
                    $builder->getModel()->getTable().'.tenant_id',
                    auth()->user()->tenant_id
                );
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
