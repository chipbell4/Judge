<?php namespace Judge\Models;

class SolutionState extends Base
{
    protected $fillable = ['pending', 'is_correct'];

    /**
     * The validation rules for a solution state
     */
    public static $rules = array(
        'name'=>'required'
    );

    /**
     * Gets all of the solutions with a give solution state
     */
    public function solutions()
    {
        return $this->hasMany('Judge\Models\Solution');
    }

    public function getBootstrapColorAttribute()
    {
        if ($this->pending) {
            return 'info';
        }

        if ($this->is_correct) {
            return 'success';
        }

        return 'danger';
    }
}
