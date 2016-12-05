<?php

/**
 * Created by PhpStorm.
 * User: John
 * Date: 11/10/2016
 * Time: 9:17 PM
 */
class UserAction extends Elegant {

    protected $fillable = ['user_id', 'action_id', 'action_value', 'target_id', 'target_type_id'];

    /**
     * Master data
     *
     * @var array
     */
    public static $actions = [
        1   => 'LIKE',
        2   => 'RATE',
        3   => 'COMMENT',
    ];

    public static $target_type = [
        1 => 'Post',
        2 => 'Comment'
    ];

    public static $target_type_table_mapping = [
        'posts'                 => 1,
        'comments'              => 2,
    ];

    public static $type_name = [
        1 => 'Post',
        2 => 'Comment',
    ];

    public static $action_target_mapping = [
        1   => [1],
        2   => [1],
        3   => [2]
    ];

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
        self::observe(new UserActionObserver);
    }


    /**
     * Accessors
     */
    public function getActionValueAttribute($value='')
    {
        if(isJSON($value)){
            return json_decode($value);
        }
        return $value;
    }

    /**
     * Mutators
     */

    /**
     * Relationships
     */
    public function post()
    {
        return $this->belongsTo('Post', 'target_id', 'id');
    }

    public function comment()
    {
        return $this->belongsTo('Comment', 'target_id', 'id');
    }
//
//    public function target()
//    {
//        $model = self::$target_type[$this->target_type_id];
//        return $this->belongsTo($model, 'target_id', 'id');
//    }

    /**
     * Scope
     */
    public function scopeLatest()
    {
        return $this->orderBy($this->absColumn('created_at'), 'desc')->limit(2);
    }

    public function scopeNotifications($query)
    {
        $targetActions = [2, 10, 11];
        $query->whereIn('action_id', $targetActions);

        return $query;
    }

    /**
     * Accessors
     */
    public function getTypeNameAttribute()
    {
        return static::$type_name[$this->target_type_id];
    }

    public function getLinkToRelatedContentAttribute()
    {
        if(in_array($this->target_type_id, [1,2,3])) {
            switch ($this->action_id) {
                case 10:
                    $article = $this->target;
                    break;
                case 16:
                case 17:
                default:
                    $article = Article::find($this->action_value);
                    break;
            }

            if($article) {
                $routebase = lcfirst(str_plural($this->typeName));
                return link_to_route("admin.$routebase.show",  $article->title ,  $article->id);
            }
        }

        return '';
    }

    /**
     * Overring toArray
     */
    public function toArray()
    {
        $this->load('target');
        $data = parent::toArray();
        return $data;
    }

    /**
     * Other
     */
    public static function getModelByTargetTypeId($targetId)
    {
        return static::$target_type[$targetId];
    }

    public static function getTargetTypeIdByTable($table)
    {
        return static::$target_type_table_mapping[$table];
    }

    // Parse notifcation
    public function toMessage()
    {
        $message = "";
        $target_type = "";
        $article = "A";

        // get target type
        switch ($this->target_type_id) {
            case '1':
                $article = "An";
                $target_type = 'article';
                break;

            case '2':
                $target_type = 'news';
                break;

            case '3':
                $target_type = 'lesson';
                break;
            case '5':
                $article = "An";
                $target_type = 'event';
                break;
            case '9':
                $article = "An";
                $target_type = 'announcement';
                break;

        }

        // action
        switch ($this->action_id) {
            case '2':
                $message = $this->action_value . "interested to meet you";
                break;

            case '5':
                $message = "You have been signed up to " . $this->action_value . " event";
                break;

            case '9':
                $message = "There is a new comment on your discussion";
                break;

            case '12':
                $message = "There is a new ";
                $message = $message . $target_type;
                break;

            case '14':
                $message = "$article $target_type has been canceled";
                break;

            case '15':
                $message = "$article $target_type has been updated";
                break;
        }

        return $message;
    }

    // Translate action to nature language
    public function translate()
    {
        $text = '';
        $createdByUser = $this->created_by_user->first_name;
        switch ($this->action_id) {
            case 10: // Create
                $text =  "$createdByUser created new " . lcfirst($this->typeName);
                break;
            case 16:
                $clonedToArticle = Article::find($this->action_value);
                if($clonedToArticle) {
                    $text =  "$createdByUser cloned into " . $clonedToArticle->title;
                }
                # code...
                break;
            case 17:
                # code...
                break;

            default:
                # code...
                break;
        }

        return $text;
    }

    // Get content type by request url
    public static function getContentTypeIdByRequestUrl($url)
    {
        $segments = explode("/", $url);
        if(in_array('events', $segments)) {
            return 5;
        }

        foreach (static::$type_name as $key => $type) {
            $type = lcfirst($type);
            if(in_array(str_plural($type), $segments)) {
                return $key;
            }
        }

        return null;
    }

}