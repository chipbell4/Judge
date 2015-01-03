<?php namespace Judge\Models\Problem;

use Judge\Models\Base;

class Problem extends Base
{
    public static $rules = array(
        'name' => 'required',
        'contest_id' => 'required',
        'judging_input' => 'required',
        'judging_output' => 'required'
        );

    /**
     * Gets the contest that this problem belongs to
     */
    public function contest()
    {
        return $this->belongsTo('Judge\Models\Contest\Contest');
    }

    /**
     * Gets all solutions for this problem
     */
    public function solutions()
    {
        return $this->hasMany('Judge\Models\Solution\Solution');
    }

    /**
     * Gets the problems for the current contest
     */
    public function scopeForCurrentContest($query)
    {
        $contests = Contest::current()->first();
        if ($contests == null) {
            return Problem::where('id', null);
        }
        return $query->where('contest_id', $contests->id)->orderBy('created_at');
    }
}
