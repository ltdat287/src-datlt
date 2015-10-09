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
     * @param string $key [name input of form]
     * @param string $group [name of session]
     * @return string [string to display in form]
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
     * @return string [date of maxdate]
     */
    public static function getMaxDate()
    {
        return Carbon::now()->subYears(VP_DATE_LIMIT_YEAR)->year . '-01-01';
    }

    /**
     * Check current user is loggin.
     *
     * @return object [return object User]
     */
    public static function checkLogin()
    {
        $user = Auth::user();
        return $user;
    }

    /**
     * Get current user role.
     *
     * @return string [return current role of user]
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
     * @param string $id [id of user loggin]
     * @return boolean
     */
    public static function showEditButton($id = '')
    {
        if (! $id) return false;

        $role = self::getCurrentUserRole();
        $allow = false;
        $member = User::find($id);

        if ($role == ADMIN
            || $role == BOSS && Auth::user()->id == $member->boss_id
            || $role == EMPLOYEE && Auth::user()->id == $id) {
            $allow = true;
        }

        return $allow;
    }

    /**
     * Get name of role member
     * @param  string $role value role of user
     * @return [string]       [name of role user]
     */
    public static function getNameRole($role = '')
    {
        switch ($role) {
            case ADMIN:
                $name_role = '管理者';
                break;
            case BOSS:
                $name_role = 'BOSS';
                break;
            case EMPLOYEE:
                $name_role = '従業員';
                break;
            default:
                $name_role = '';
                break;
            }

        return $name_role;
    }
}
