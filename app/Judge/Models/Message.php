<?php namespace Judge\Models;

use Judge\Models\Base;

class Message extends Base
{
    public static $rules = array(
        'sender_id' => 'required',
        'text' => 'required'
    );

    protected $guarded = array('id', 'created_at', 'updated_at');

    /**
     * Gets the contest associated with this message
     */
    public function contest()
    {
        return $this->belongsTo('Judge\Models\Contest');
    }

    public function problem()
    {
        return $this->belongsTo('Judge\Models\Problem');
    }

    public function sender()
    {
        return $this->belongsTo('Judge\Models\User', 'sender_id');
    }

    public function responder()
    {
        return $this->belongsTo('Judge\Models\User', 'responder_id');
    }

    public function getResponseTextAttribute()
    {
        return nl2br($this->attributes['response_text']);
    }

    public function getTextAttribute()
    {
        return nl2br($this->attributes['text']);
    }
}
