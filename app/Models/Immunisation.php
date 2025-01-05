<?php

namespace App\Models;

use App\Models\Scopes\NurseryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Immunisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nursery_id',
        'name',
        'description',
        'requires_dates',
    ];

    protected $casts = [
        'requires_dates' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new NurseryScope);
    }

    public function children()
    {
        return $this->belongsToMany(Child::class, 'child_immunisation')
            ->withPivot(['date_given', 'date_due', 'notes'])
            ->withTimestamps();
    }

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }
}
