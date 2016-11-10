<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/1/2016
 * Time: 8:01 PM
 */
class PostLike extends Elegant
{
    protected $table = 'post_shares';

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


    /**
     * Boot Medthod
     */
    public  static function boot()
    {
        parent::boot();

        // Register observer
        self::observe(new PostShareObserver);
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