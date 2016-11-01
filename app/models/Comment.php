<?php

use Codesleeve\Stapler\ORM\EloquentTrait;
use \Codesleeve\Stapler\ORM\StaplerableInterface ;

class Comment extends Elegant implements StaplerableInterface
{
    use EloquentTrait;

    protected $fillable = ['content'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['target_id', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

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
//        parent::boot();
//        static::bootStapler();

        // Register observer
        self::observe(new CommentObserver);
    }

}
