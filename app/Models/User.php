<?php

namespace App\Models;

use App\Models\Scopes\NurseryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected static function booted()
    {
        static::addGlobalScope(new NurseryScope);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'is_admin',
        'nursery_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }

    public function scopeForNursery($query, $nurseryId)
    {
        return $query->where('nursery_id', $nurseryId);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_staff')
            ->withPivot('is_room_leader')
            ->withTimestamps();
    }
}
