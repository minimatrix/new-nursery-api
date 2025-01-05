<?php

namespace App\Models;

use App\Models\Scopes\NurseryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nursery_id',
        'name',
        'description',
        'capacity',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new NurseryScope);
    }

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }

    public function children()
    {
        return $this->hasMany(Child::class);
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'room_staff')
            ->withPivot('is_room_leader')
            ->withTimestamps();
    }

    public function roomLeaders()
    {
        return $this->staff()->wherePivot('is_room_leader', true);
    }
}
