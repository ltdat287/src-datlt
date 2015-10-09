<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

/**
 * User model used to get info of member from database
 */
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that need date form to process date-time of Carbon
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'birthday'];

    /**
     * [getUsers Get user info where not disabled and older by updated_at DESC]
     * @return [collection]
     */
    public static function getUsers()
    {
        $results = self::where('disabled', '=', false)->orderBy('updated_at', 'DESC');

        return $results;
    }
    /**
     * Get bosses not disable from db.
     *
     * @return objects
     */
    public static function getBosses()
    {
        $results = self::where('disabled', '=', false)->where('role', '=', BOSS);

        return $results;
    }
}
