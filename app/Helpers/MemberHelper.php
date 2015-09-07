<?php namespace App\Helpers;

use Auth;
use Session;
use Input;
use Route;
use Carbon\Carbon;
use App\User;

class MemberHelper
{
    /**
     * Function to get cache data for form
     * 
     * @param string $key
     * @param string $group
     * @return string
     */
    public static function getOld($key = '', $group = 'user')
    {
        $session = Session::get($group);
        if (!empty($session->$key)) {
            return $session->$key;
        } else {
            return Input::old($key);
        }
    }
    
    /**
     * Get max date format {now year - 10year}-01-01
     * 
     * @return string
     */
    public static function getMaxDate()
    {
        return Carbon::now()->subYears(VP_DATE_LIMIT_YEAR)->year . '-01-01';
    }
    
    /**
     * Check current user is loggin.
     * 
     * @return object
     */
    public static function checkLogin()
    {
        $user = Auth::user();
        return $user;
    }
    
    /**
     * Get current user role.
     * 
     * @return string
     */
    public static function getCurrentUserRole()
    {
        $user = self::checkLogin();
        $role = '';
        if ($user)
        {
            $role = $user->role;
            $role = ($role) ? $role : '';
        }
        return $role;
    }
    
    /**
     * Check allow show edit button
     * 
     * @param string $id
     * @return boolean
     */
    public static function showEditButton($id = '')
    {
        if (! $id) return false;
        
        $role = self::getCurrentUserRole();
        $allow = false;
        $member = User::find($id);
        
        if ($role == 'admin' 
            || $role == 'boss' && Auth::user()->id == $member->boss_id
            || $role == 'employee' && Auth::user()->id == $id) {
            $allow = true;
        }
        
        return $allow;
    }
}
