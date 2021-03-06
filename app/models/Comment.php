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
        parent::boot();
        static::bootStapler();

        // Register observer
        self::observe(new CommentObserver);
    }

    /**
     * Rules
     *
     * @var array
     */
    public static $rules = array(
        'content'                  => 'required',
    );

    /**
     * Accessors
     */
    public function getTargetAttribute()
    {
        return $this->target()->first();
    }
    public function getTargetTypeIdAttribute()
    {
        return $this->target->type_id;
    }

    /**
     * Relationships
     */
    public function post()
    {
        return $this->belongsTo('Post','target_id', 'id');
    }

    public function target()
    {
        $model = self::$table_model_mapping[$this->target_type];
        return $this->belongsTo($model, 'target_id', 'id');
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
    public function toArray()
    {
        $this->load('created_by_user');

        $data = parent::toArray();
        if(!isset($data['parent_id'])){
            $data['parent_id'] = $this->parent_id;
        }
        return $data;
    }

}
