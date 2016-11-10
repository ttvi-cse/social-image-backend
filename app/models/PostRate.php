<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/1/2016
 * Time: 8:01 PM
 */
class PostRate extends Elegant
{
    protected $table = 'post_rates';

    /**
     * Fillable
     */
    protected $fillable = ['created_by', 'post_id'];

    /**
     * Master data
     *
     * @var array
     */

    /**
     * Rules
     *
     * @var array
     */
    public static $rules = array(
        'grade'                  => 'required|integer|min:1|max:5'
    );

    /**
     * Boot Medthod
     */
    public  static function boot()
    {
        parent::boot();

        // Register observer
        self::observe(new PostRateObserver);
    }

    /**
     * Accessors
     */

    /**
     * Mutators
     */

    /**
     * Relationships
     */
    public function post()
    {
        return $this->belongsTo('Post', 'post_id', 'id');
    }
}