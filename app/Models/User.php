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

    private $_questions = null;

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
     * Questions relation
     *
     * @return  Question    collection
     */
    public function questions()
    {
        if (!is_null($this->_questions))
            return $this->_questions;

        $cache = Cache::get("{$this->username}:questions");

        if (!is_null($cache))
        {
            $this->_questions = $cache;
            return $this->_questions;
        }

        $questions = $this->hasMany('App\Models\Question', 'user_id', 'id')->get();

        $this->_questions = $questions;
        Cache::put("{$this->username}:questions", $this->_questions, 5);

        return $this->_questions;
    }
}
