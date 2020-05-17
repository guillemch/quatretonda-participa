<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'edition_id', 'question_id', 'option_id', 'points'
    ];

    /**
     * A result belongs to a question
     */
    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    /**
     * A result belongs to an option
     */
    public function option()
    {
        return $this->belongsTo('App\Option');
    }

}
