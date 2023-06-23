<?php

namespace App\Models\SSO\Web;

use RistekUSDI\SSO\Laravel\Models\Web\User as Model;
use App\Facades\WebSession;

class User extends Model
{
    protected $appends = ['roles', 'role_active', 'role_active_permissions'];

    public function getRolesAttribute()
    {
        return $this->getAttribute('client_roles');
    }

    public function setRoleActiveAttribute($value)
    {
        $this->attributes['role_active'] = WebSession::setRoleActive($value);
    }

    public function getRoleActiveAttribute()
    {
        return $this->attributes['role_active'] = WebSession::getRoleActive($this->roles['0']);
    }

    // Ini digunakan untuk mengubah role active dalam session internal aplikasi
    public function changeRoleActive($role_active)
    {
        WebSession::changeRoleActive($role_active);
    }
}
