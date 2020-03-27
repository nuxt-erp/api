<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class User extends LoginModel implements ModelInterface
{
    const USER_ROLE     = 'user';
    const ADMIN_ROLE    = 'admin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Params.
     *
     * @var request
     * @var item
     */

    public function getRules($request, $item = null)
    {

        $rules = [
            'name'          => ['string', 'max:255'],
            'email'         => ['max:255', 'email'],
            'password'      => ['between:4,32'],
            'roles'         => ['array']

        ];
        //create
        if (is_null($item)) {
            $rules['name'][]        = 'required';
            $rules['email'][]       = 'required';
            $rules['email'][]       = 'unique:users';
            $rules['password'][]    = 'required';
            $rules['roles'][]       = 'required';
        } else {
            //update
            $rules['email'][]       = Rule::unique('users')->ignore($item->id);
        }

        return $rules;
    }

    public function getClientIdAttribute()
    {
        return $this->token()->client_id;
    }

    public function setAsAdmin()
    {
        $admin_role = Role::where('name', self::ADMIN_ROLE)->first();

        $instance = $admin_role ? UsersRoles::create([
            'user_id' => $this->id,
            'role_id' => $admin_role->id
        ]) : null;

        return $instance;
    }

    public function setAsUser()
    {
        $user_role = Role::where('name', self::USER_ROLE)->first();

        $instance = $user_role ? UsersRoles::create([
            'user_id' => $this->id,
            'role_id' => $user_role->id
        ]) : null;

        return $instance;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function hasRole(... $roles){
        foreach ($roles as $role) {
            if ($this->roles->contains('name', $role)) {
                return true;
            }
        }
        return false;
    }

    public function findForPassport($login)
    {
        return $this->where('email', $login)->first();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles')->withTimestamps();
    }

    public function getAuthorizedPhases(){
        $phases = [];
        foreach ($this->roles as $role) {
            foreach ($role->operations as $operation) {
                foreach ($operation->phases as $phase) {
                    $phases[$phase->id] = $phase;
                }
            }
        }
        return $phases;
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
