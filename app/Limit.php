<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Limit extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the edition that the log belongs to.
     */
    public function edition()
    {
        return $this->belongsTo('App\Edition');
    }

    /**
     * Log an action taken by an IP
     *
     * @return boolean
     */
    public static function logAction($action, $editionId = null, $value = null)
    {
        $editionId = ($editionId) ? $editionId : Edition::current()->id;
        $limit = new Self;

        $limit->edition_id = $editionId;
        $limit->ip = Self::ip();
        $limit->action = $action;
        $limit->value = $value;
        $limit->user_agent = Self::userAgent();

        return $limit->save();
    }

    /**
     * Check if an IP has exceeded an action's limit
     *
     * @return boolean
     */
    public static function exceeded($action, $limit, $editionId = null)
    {
        $editionId = ($editionId) ? $editionId : Edition::current()->id;
        $count = Self::where('ip', '=', Self::ip())
                    ->where('action', '=', $action)
                    ->where('edition_id', $editionId)
                    ->count();

        return $count >= $limit;
    }

    /**
     * Groups
     *
     * @return boolean
     */
    public static function getReports($editionId, $action = 'vote')
    {
        $editionId = ($editionId) ? $editionId : Edition::current()->id;
        $limit = ($action == 'vote')
            ? config('participa.max_per_ip')
            : config('participa.max_failed_lookups');
        $reports = [];

        $ips = Self::select('ip')
                    ->where('edition_id', $editionId)
                    ->where('action', $action)
                    ->groupBy('ip')
                    ->havingRaw('COUNT(ip) >= ' . $limit)
                    ->get();

        $dates = Self::select('ip', 'created_at')
                    ->where('edition_id', $editionId)
                    ->where('action', $action)
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy('ip')
                    ->toArray();

        foreach ($ips as $ip) {
            $reports[] = [
                'type' => 'limit',
                'ip' => $ip->ip,
                'action' => $action,
                'created_at' => $dates[$ip->ip][0]['created_at']
            ];
        }

        return $reports;
    }

    /**
     * Groups
     *
     * @return boolean
     */
    public static function unblock($ip, $editionId = null)
    {
        $editionId = ($editionId) ? $editionId : Edition::current()->id;

        return Self::where('edition_id', $editionId)
            ->where('ip', $ip)
            ->delete();
    }

    /**
     * Returns the user IP, accounting for Cloudflare (consider modifying ip())
     *
     * @return string
     */
    public static function ip()
    {
        return (isset($_SERVER['HTTP_CF_CONNECTING_IP']))
            ? $_SERVER['HTTP_CF_CONNECTING_IP']
            : \Request::ip();
    }

    /**
     * Returns the User Agent
     *
     * @return string
     */
    public static function userAgent() {
        return \Request::header('User-Agent');
    }
}
