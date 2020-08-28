<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class User extends LoginModel implements ModelInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'is_enabled', 'disabled_at', 'company_id'
    ];

    protected $dates = [
        'disabled_at'
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
            'company_id'    => ['nullable', 'exists:companies,id'],
            'name'          => ['string', 'max:255'],
            'email'         => ['max:255', 'email'],
            'password'      => ['between:4,32'],
            'roles'         => ['array'],
            'is_enabled'    => ['boolean'],
            'disabled_at'   => ['nullable', 'date']

        ];
        //create
        if (is_null($item)) {
            $rules['name'][]        = 'required';
            $rules['email'][]       = 'required';
            $rules['email'][]       = 'unique:users';
            $rules['password'][]    = 'required';
        } else {
            //update
            $rules['email'][]       = Rule::unique('users')->ignore($item->id);
        }

        return $rules;
    }

    public function findForPassport($login)
    {
        return $this->where('email', $login)->first();
    }

    public function setRole($role)
    {
        $set_role = Role::where('name', $role)
        ->orWhere('code', $role)
        ->first();
        if(!$set_role){
            $set_role = Role::create(['name' => $role, 'code' => $role]);
        }

        $instance = $set_role ? UserRoles::create([
            'user_id' => $this->id,
            'role_id' => $set_role->id
        ]) : null;

        return $instance;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function hasRole(... $roles){
        foreach ($roles as $role) {
            if ($this->roles->contains('name', $role) || $this->roles->contains('code', $role)) {
                return true;
            }
        }
        return false;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
