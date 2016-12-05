<?php

use Codesleeve\Stapler\ORM\EloquentTrait;
use \Codesleeve\Stapler\ORM\StaplerableInterface ;

class Location extends Elegant implements StaplerableInterface
{
    use EloquentTrait;

    protected $fillable = ['id', 'place_id', 'name', 'address', 'phone'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    /**
     * Overwrite Constructor
     *
     */
    public function __construct(array $attributes = array()) {
        // Config for upload all file types
        parent::__construct($attributes);
    }

    /**
     * Boot Medthod
     */
    public static function boot()
    {
        parent::boot();
        static::bootStapler();

        // Register observer
        self::observe(new LocationObserver());
    }

    /**
     * Rules
     *
     * @var array
     */
    public static $rules = array(
        'place_id'  => 'required'
    );

    /**
     * Accessors
     */

    /**
     * Relationships
     */
    public function post()
    {
        return $this->hasMany('Post','location_by', 'id');
    }

    /**
     * Scopes
     */
    public function scopeLatest($query)
    {
        return $query->orderBy($this->absColumn('created_at'), 'desc')->limit(2);
    }

    /**
     * Override toArray
     */

}
