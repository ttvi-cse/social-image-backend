<?php

use Codesleeve\Stapler\ORM\EloquentTrait;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserTrait;

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
        'first_name' => 'required|string|max:50',
        'last_name' => 'string|max:50',
        'job_title' => 'string|max:255',
        'gender' => 'integer|in:1,2',
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
//        parent::boot();
//        static::bootStapler();
        // Register observer
        self::observe(new UserObserver);
    }
}
