<?php

use \Codesleeve\Stapler\ORM\StaplerableInterface;
use \Codesleeve\Stapler\ORM\EloquentTrait;

class Post extends Elegant implements StaplerableInterface
{
    use EloquentTrait;

    /**
     * Fillable
     */
    protected $fillable = ['image', 'content', 'location'];

    /**
     * Appends attributes
     */
    protected $appends = ['thumb', 'is_liked', 'is_rated'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['image_file_name', 'image_file_size', 'image_content_type', 'image_updated_at','updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    public static $rules = array(
//        'file' => 'required',
        'content' => 'required'
    );

    public function __construct(array $attributes = array()) {
        $this->hasAttachedFile('image', [
            'styles' => [
                'small' => '100x100',
                'medium' => '300x300',
                'large' => '600x600',
            ],
        ]);

        parent::__construct($attributes);
    }

    public static function boot() {
        parent::boot();
        static::bootStapler();

        // Register observer
        self::observe(new PostObserver);
    }

    /**
     * Accessors
     *
     */
    public function getThumbAttribute() {
        $basename = Config::get("app.url");
        \Log::info($this->image->url());
        return $basename . $this->image->url('large');
    }

    public function getIsLikedAttribute() {
        $user = API::user();
        if (!$user) {
            return false;
        }

        return $this->likes()->where('created_by', $user->id)->count() > 0;
    }

    public function getIsRatedAttribute() {
        $user = API::user();
        if (!$user) {
            return false;
        }

        return $this->rates()->where('created_by', $user->id)->count() > 0;
    }

    public function getCreatedByNameAttribute() {
        $user = User::find($this->created_by);
        $name = '';
        if ($user) {
            $name = $user->full_name;
        }
        return $name;
    }

//    public function getCreatedDateAttribute() {
//        $date = date_create($this->created_at);
//
//        return date_format($date, 'Y-m-d');
//    }

    /**
     * Mutators
     */

    /**
     * Relationships
     */

    public function likes() {
        return $this->hasMany('PostLike');
    }

    public function rates() {
        return $this->hasMany('PostRate');
    }

    public function comments() {
        return $this->hasMany('Comment', 'target_id', 'id');
    }

    public function vendors() {
        return $this->belongsTo('Vendor');
    }

    public function users() {
        return $this->belongsTo('User');
    }

    /**
     * Scopes
     */
    public function scopeSort($query) {
        $input = [
            'sort' => Input::get('sort', 'title'),
            'order' => Input::get('order', 'asc'),
        ];

        $query->orderBy($this->absColumn($input['sort']), $input['order']);

        return $query;
    }

    // Increase view count
    public function increaseViewCount() {
        $this->view_count++;
        $this->save();
    }

    /**
     * Update rating average
     */
    public function updateRatingAverage($newRate) {
        $n = $this->rating_count;
        $x = $this->rating_average;
        $y = $newRate;
        $r = (($n * $x) + $y) / ($n + 1);

        $this->rating_average = ceil($r * 2) / 2;

        return $this;
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
