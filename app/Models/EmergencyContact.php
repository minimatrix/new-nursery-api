<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'name',
        'relationship',
        'phone',
        'alternative_phone',
        'priority',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
