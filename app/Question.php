<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * Get the edition that the question belongs to.
     */
    public function edition()
    {
        return $this->belongsTo('App\Edition');
    }

    /**
     * Get the options in the question.
     */
    public function options()
    {
        return $this->hasMany('App\Option');
    }

    /**
     * Get the results for the options beloging to the question.
     */
    public function results()
    {
        return $this->hasMany('App\Result');
    }
}
