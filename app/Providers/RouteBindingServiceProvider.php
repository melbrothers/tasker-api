<?php

namespace App\Providers;

use mmghv\LumenRouteBinding\RouteBindingServiceProvider as BaseServiceProvider;
use App\Models\Task;

class RouteBindingServiceProvider extends BaseServiceProvider
{
    /**
     * Boot the service provider
     */
    public function boot()
    {
        // The binder instance
        $binder = $this->binder;

        // Here we define our bindings
        $binder->implicitBind('App\Models');

        // Using a closure
        $binder->bind('task', function($value) {
            if ($task = Task::find($value)) {
                return $task;
            }

            return Task::where('slug', $value)->firstOrFail();
        });
    }
}
