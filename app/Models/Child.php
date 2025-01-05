<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Child extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nursery_id',
        'room_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'notes',
        'grant_eligible',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'grant_eligible' => 'boolean',
    ];

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }

    public function guardians()
    {
        return $this->hasMany(Guardian::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function allergies()
    {
        return $this->belongsToMany(Allergy::class, 'child_allergy')
            ->withPivot(['notes', 'severity', 'symptoms', 'treatment'])
            ->withTimestamps();
    }

    public function dietaryRequirements()
    {
        return $this->belongsToMany(DietaryRequirement::class, 'child_dietary_requirement')
            ->withPivot('notes')
            ->withTimestamps();
    }

    public function immunisations()
    {
        return $this->belongsToMany(Immunisation::class, 'child_immunisation')
            ->withPivot(['date_given', 'date_due', 'notes'])
            ->withTimestamps();
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getCurrentPricingBand(): ?PricingBand
    {
        $ageInMonths = $this->date_of_birth->diffInMonths(now());

        return PricingBand::where('nursery_id', $this->nursery_id)
            ->where('min_age_months', '<=', $ageInMonths)
            ->where('max_age_months', '>=', $ageInMonths)
            ->first();
    }
}
