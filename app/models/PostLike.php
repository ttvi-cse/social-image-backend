<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/10/2016
 * Time: 9:50 PM
 */
class PostLike extends Elegant 
{
    protected $table = 'post_likes';

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
        self::observe(new PostLikeObserver());
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
        return $this->belongsTo('post', 'post_id', 'id');
    }
}