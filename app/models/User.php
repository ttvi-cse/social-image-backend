<?php

use Codesleeve\Stapler\ORM\EloquentTrait;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class User extends Elegant implements UserInterface, RemindableInterface, StaplerableInterface {

    use UserTrait, RemindableTrait, EloquentTrait;

    /**
     * Fillable
     */
    protected $fillable = ['avatar', 'first_name', 'last_name', 'username', 'gender', 'password', 'password_confirmation', 'status_id',
        'email', 'job_title', 'business_interest', 'summary', 'name_title', 'type_id', 'organization'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['pivot', 'password', 'reDeveloper_token', 'android_device_token', 'avatar_file_name',
        'avatar_file_size', 'avatar_content_type', 'avatar_updated_at', 'status_id',
        'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    /**
     * Master data
     *
     * @var array
     */
    public static $types = [
        1 => 'Admin',
        2 => 'Trainer',
        3 => 'Developer',
        4 => 'Trainee',
    ];

    public static $types_form = [
        2 => 'Trainer',
        3 => 'Developer',
        4 => 'Trainee',
    ];

    public static $gender = [
        1 => 'Male',
        2 => 'Female',
    ];

    public static $status = [
        1 => 'Active',
        2 => 'Inactive',
    ];

    public static $Developer_ship = [
        1 => 'Active',
        2 => 'Expired',
    ];

    public static $name_title_arr = [
        'Mr' => 'Mr',
        'Ms' => 'Ms',
        'Mrs' => 'Mrs',
        'Prof' => 'Prof',
        'Dr' => 'Dr',
    ];

    /**
     * Rules
     *
     * @var array
     */
    public static $rules = [
        'username' => 'required|between:4,50|unique:users,username',
        'email' => 'email|unique:users,email',
        // 'first_name' => 'required|string|max:50',
        // 'last_name' => 'string|max:50',
        // 'job_title' => 'string|max:255',
        // 'gender' => 'integer|in:1,2',
        'password' => 'required|between:6,16|alpha_num|confirmed',
        'password_confirmation' => 'required',
    ];

    public static $rules_change_password = [
        'old_password' => 'required',
        'password' => 'required|between:6,16|alpha_num|confirmed',
        'password_confirmation' => 'required',
    ];

    /**
     * Overwrite Constructor
     *
     */
    public function __construct(array $attributes = array()) {
        $this->hasAttachedFile('avatar', [
            'styles' => [
                'small' => '100x100',
                'medium' => '300x300',
            ],
        ]);

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
        self::observe(new UserObserver);
    }

    /**
     * Relationships
     */

    public function posts() {
        return $this->hasMany('Post', 'created_by', 'id');
    }

    public function comments() {
        return $this->hasMany('Comment', 'created_by');
    }

    /**
     * Others
     */

    // Comment on an object
    public function comment($target, $content, $parentId = NULL) {
        $comment = new Comment([
            'content' => $content,
        ]);
        $comment->target_id = $target->id;
//        $comment->target_type = $target->getTable();

        if ($parentId) {
            $comment->parent_id = $parentId;
        }

        $this->comments()->save($comment);

        return $comment;
    }

    // like an article
    public function like($post) {
        $post = PostLike::firstOrNew([
            'created_by' => $this->id,
            'post_id' => $post->id,
        ]);

        if (isset($post->id)) {
            $this->addError('post_liked', Lang::get('flash_messages.post_liked'));
            return false;
        }

        if (!$post->save()) {
            $this->validationErrors = $post->errors();
            return false;
        }

        return true;
    }

    // rate an article
    public function rate($post, $grade) {
        $rate = PostRate::firstOrNew([
            'created_by' => $this->id,
            'post_id' => $post->id,
        ]);

        if (isset($rate->id)) {
            $this->addError('post_rated', Lang::get('flash_messages.post_rated'));
            return false;
        }

        $rate->grade = $grade;

        if (!$rate->save()) {
            $this->validationErrors = $rate->errors();
            return false;
        }

        return true;
    }

    // Attach an user action
    public function attachAction($actionId, $actionValue, $targetId, $targetTypeId) {

        \Log::info('action_id' . $actionId);
        $modelName = UserAction::$target_type[$targetTypeId];
        $actionName = UserAction::$actions[$actionId];

        if (!in_array($targetTypeId, UserAction::$action_target_mapping[$actionId])) {
            if ($modelName == 'MyEvent') {
                $modelName = 'Event';
            }
            $this->addError('user_action_unsupported', Lang::get('flash_messages.user_action_unsupported', ['a' => $actionName, 'o' => $modelName]));
            return false;
        }

        $target = $modelName::find($targetId);
        if (!$target) {
            App::abort(404);
        }

        switch ($actionId) {
            case 1:
                $result = $this->like($target);
                break;
            case 2:
                $result = $this->rate($target, $actionValue);
                break;

            default:
                $result = true;
                break;
        }

        if (!$result) {
            return false;
        }

        $action = UserAction::create([
            'user_id' => $this->id,
            'action_value' => $actionValue,
            'action_id' => $actionId,
            'target_id' => $targetId,
            'target_type_id' => $targetTypeId,
        ]);

        return true;
    }
}
