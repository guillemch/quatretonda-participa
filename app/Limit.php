<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Limit extends Model
{

    /**
     * Log an action taken by an IP
     *
     * @return boolean
     */
    public static function logAction($request, $action)
    {
        $limit = new Self;

        $limit->ip = $request->ip();
        $limit->action = $action;
        $limit->user_agent = $request->header('User-Agent');

        return $limit->save();
    }

    /**
     * Check if an IP has exceeded an action's limit
     *
     * @return boolean
     */
    public static function exceeded($request, $action, $limit)
    {
        $count = Self::where('ip', '=', $request->ip())->where('action', '=', $action)->count();

        return $count >= $limit;
    }
}
