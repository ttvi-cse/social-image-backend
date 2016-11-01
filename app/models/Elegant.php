<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\MessageBag;

class Elegant extends Eloquent{

    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    public $validationErrors = null;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    /**
     * Appends attributes
     */
    protected $appends = [];

    /**
     * Validation Rules
     */
    public static $rules = [];

    public function getRules()
    {
        return static::$rules;
    }
    
    public function getUpdateRules()
    {
        $updateRules = self::$rules;
        $sometimes = 'sometimes|';
        foreach ($updateRules as $key => $value) {
            if(!str_contains($value, $sometimes)){
                $updateRules[$key] = $sometimes . $value;    
            }
        }

        return $updateRules;
    }

    /**
     * Overwrite Constructor
     *
     */
    public function __construct(array $attributes = array()) {
        $this->validationErrors = new MessageBag;

        parent::__construct($attributes);
    }

    /**
     * Boot Medthod
     */
    public static function boot()
    {
        parent::boot();

        // Register observer
        self::observe(new ElegantObserver);
    }

	/**
	 * Accessors
	 */
	public function getCreatedAtAttribute($value)
    {
        return dt_format($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return dt_format($value);
    }

    public function getDeletedAtAttribute($value)
    {
        return dt_format($value);
    }

    public function getCreatedByUserAttribute()
    {
        return $this->created_by_user()->withTrashed()->first();
    }

    public function getUpdatedByUserAttribute()
    {
        return $this->updated_by_user()->withTrashed()->first();
    }

    public function getDeletedByUserAttribute()
    {
        return $this->deleted_by_user()->first();
    }

    /**
     * Relationships
     */
    public function created_by_user()
    {
        return $this->belongsTo('User', 'created_by');
    }

    public function updated_by_user()
    {
        return $this->belongsTo('User', 'updated_by');
    }

    public function deleted_by_user()
    {
        return $this->belongsTo('User', 'deleted_by');
    }

    /**
     * Scope
     */
    public function scopeFilter($query, $input)
    {
        return $query;
    }

    public function scopeSort($query)
    {
        $input = [
            'sort' => Input::get('sort', 'created_at'), 
            'order' => Input::get('order', 'desc')
        ];

        $query->orderBy($this->absColumn($input['sort']), $input['order']);

        return $query;
    }

    public function scopeDefault($query)
    {
        return $query;
    }

    /**
     * Others
     */
    public function errors(){
        return $this->validationErrors;
    }

    public function absColumn($col)
    {
        return db_abscolumn($this->getTable(), $col);
    }

    public function addError($key, $message)
    {
        $this->validationErrors->add($key, $message);
    }

    public function loadAttribute($key)
    {
        if(!in_array($key, $this->appends)){
            $this->appends[] = $key;
        }
    }

    public function toSmallArray(){
        return $this->getAttributes();
    }

    public function isOwner($uid = null)
    {
        $user = Auth::user();
        if(!$user){
            return false;
        }

        if (is_null($uid)) {
            $uid = $user->id;
        }
        return $this->created_by == $uid;
    }

    /**
     * Validate model
     */
    public function isValid($rules, $attrs = null)
    {   
        if(is_null($attrs)){
            $attrs = $this->getAttributes();        
        }

        $v = Validator::make($attrs, $rules);    

        // Check of failure
        if ($v->fails()) {
            $this->validationErrors = $v->errors();
            return false;
        }

        // Validation pass
        return true;
    }

    /**
     * Get Config Key
     */
    public static function getConfigKey()
    {
        $configKey = get_called_class();
        $configKey = str_plural($configKey);
        $configKey = strtolower($configKey);

        return $configKey;
    }
}
