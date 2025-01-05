<?php

namespace App\Models;

use App\Models\Scopes\NurseryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingBand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nursery_id',
        'name',
        'min_age_months',
        'max_age_months',
        'hourly_rate',
        'grant_rate',
        'description',
    ];

    protected $casts = [
        'min_age_months' => 'integer',
        'max_age_months' => 'integer',
        'hourly_rate' => 'decimal:2',
        'grant_rate' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new NurseryScope);
    }

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }

    public function getApplicableRate(Child $child): float
    {
        return $child->grant_eligible ? $this->grant_rate : $this->hourly_rate;
    }

    public function isAgeApplicable(Child $child): bool
    {
        $ageInMonths = $child->date_of_birth->diffInMonths(now());
        return $ageInMonths >= $this->min_age_months && $ageInMonths <= $this->max_age_months;
    }
}
