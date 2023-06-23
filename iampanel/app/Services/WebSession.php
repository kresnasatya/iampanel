<?php

namespace App\Services;

class WebSession
{
    public function setRoleActive($value)
    {
        if (session()->has('role_active')) {
            return session()->get('role_active');
        } else {
            session()->put('role_active', $value);
            session()->save();
            return $value;
        }
    }

    public function getRoleActive($role)
    {
        if (session()->has('role_active')) {
            return session()->get('role_active');
        } else {
            return $role;
        }
    }

    public function changeRoleActive($role_active)
    {
        session()->forget(['role_active']);
        session()->save();
        session()->put('role_active', $role_active);
        session()->save();
    }

    public function forgetSession()
    {
        session()->forget(['role_active']);
        session()->save();
    }
}
