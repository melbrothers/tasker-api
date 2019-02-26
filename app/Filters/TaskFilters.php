<?php

namespace App\Filters;



use Illuminate\Support\Facades\Auth;

class TaskFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'location_name',
        'min_price',
        'max_price',
        'lat',
        'lon',
        'sort_by',
        'radius',
        'task_states',
        'task_types',
        'role',
        'my_tasks',
        'limit',
        'after'
    ];

    public function sortBy($field)
    {
        if ($field == 'recent') {
            return $this->builder->orderBy('created_at');
        }

        return $this->builder;
    }

    public function radius($radius)
    {
        return $this->builder->whereGeoDistance('location.coordinate',
            [$this->request->get('lat'), $this->request->get('lon')], $radius . 'm');
    }

    public function minPrice($price)
    {
        return $this->builder->where('price', '>=', $price);
    }

    public function maxPrice($price)
    {
        return $this->builder->where('price', '<=', $price);
    }

    public function role($role)
    {
        if ($role == 'sender') {
            return $this->builder->where('sender_id', Auth::user()->id);
        } else if ($role == 'runner') {
            return $this->builder->where('runner_id', Auth::user()->id);
        }

        return $this->builder;
    }

    public function taskStates($taskStates)
    {
        $taskStates = explode(',', $taskStates);

        if (!empty($taskStates)) {
            return $this->builder->whereIn('state', $taskStates);
        }

        return $this->builder;
    }

    public function myTasks($myTasks)
    {
        if ($myTasks) {
            return $this->builder->where('sender_id', '<=', Auth::user()->id);
        }

        return $this->builder;
    }

    public function limit($limit)
    {
        return $this->builder->paginate($limit);
    }

    public function after($after)
    {
        return $this->builder;
    }
}
