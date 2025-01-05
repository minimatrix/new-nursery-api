<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Room;
use App\Policies\RoomPolicy;
use App\Models\PricingBand;
use App\Policies\PricingBandPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<string, class-string|string>
     */
    protected $policies = [
        Room::class => RoomPolicy::class,
        PricingBand::class => PricingBandPolicy::class,
    ];
}
