<?php

namespace App\Models;

use Cache;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    private $_threads = null;
    private $_permalink = null;

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
     * Return a permalink to a user's profile.
     *
     * @return  string
     */
    public function permalink()
    {
        if (!is_null($this->_permalink))
            return $this->_permalink;

        $this->_permalink = url("/u/{$this->username}");
        return $this->_permalink;
    }

    /**
     * Thread relation
     *
     * @return  Thread    collection
     */
    public function threads()
    {
        if (!is_null($this->_threads))
            return $this->_threads;

        $threads = $this->hasMany('App\Models\Thread', 'user_id', 'id')->get();
        $this->_threads = $threads;

        return $this->_threads;
    }
}
