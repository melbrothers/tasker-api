<?php

namespace App;

use App\IndexConfigurator\TaskIndexConfigurator;
use App\ScoutElastic\Searchable;
use App\Traits\Commentable;
use App\Traits\SluggableHelpers;
use App\Traits\Attachable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use Sluggable, SluggableHelpers, Attachable, Commentable, Searchable;

    const STATE_POSTED = 'posted';
    const STATE_ASSIGNED = 'assigned';
    const STATE_COMPLETED = 'completed';
    const STATE_OVERDUE = 'overdue';
    const STATE_CLOSED = 'closed';

    const STATES = [self::STATE_POSTED, self::STATE_ASSIGNED, self::STATE_CLOSED, self::STATE_COMPLETED, self::STATE_OVERDUE];

    protected $with = ['user', 'bids', 'comments', 'attachments'];

    protected $guarded = [];

    protected $indexConfigurator = TaskIndexConfigurator::class;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'specified_times' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deadline',
    ];

    public function setDeadlineAttribute($value)
    {
        $this->attributes['deadline'] = Carbon::parse($value)->toDateTimeString();
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('bidCount', function (Builder $builder) {
           $builder->withCount('bids');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['name'],
            ]
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function bid($price, $body, $user = null)
    {
        $bid = new Bid;
        $bid->price = $price;
        $bid->fee = Bid::fee($price);
        $bid->gst = Bid::gst($price);
        $bid->user()->associate($user ?: Auth::user());
        $this->bids()->save($bid);
        $bid->comment($body);

        return $bid;
    }
}
