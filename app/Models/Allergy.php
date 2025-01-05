<?php

namespace App\Models;

use App\Models\Scopes\NurseryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    use HasFactory;

    protected $fillable = [
        'nursery_id',
        'name',
        'description',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new NurseryScope);
    }

    public function children()
    {
        return $this->belongsToMany(Child::class, 'child_allergy')
            ->withPivot(['notes', 'severity', 'symptoms', 'treatment'])
            ->withTimestamps();
    }

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }
}
