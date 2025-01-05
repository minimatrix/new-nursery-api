<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'user_id',
        'first_name',
        'last_name',
        'relationship',
        'email',
        'phone',
        'is_emergency_contact',
        'can_pickup',
        'priority',
    ];

    protected $casts = [
        'is_emergency_contact' => 'boolean',
        'can_pickup' => 'boolean',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
