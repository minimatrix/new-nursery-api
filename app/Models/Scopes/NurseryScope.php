<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class NurseryScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (request()->user() && request()->user()->type !== 'super_admin') {
            $builder->where('nursery_id', request()->user()->nursery_id);
        }
    }
}
