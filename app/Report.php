<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{    
    /**
     * Get the edition that the report belongs to.
     */
    public function edition()
    {
        return $this->belongsTo('App\Edition');
    }

    /**
     * Get the user that the report belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
