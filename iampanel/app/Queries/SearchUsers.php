<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

final class SearchUsers
{
    public static function get($params)
    {
        $search = isset($params['search']) ? $params['search'] : '';
        $q = isset($params['q']) ? $params['q'] : [];

        $users_query = DB::connection('keycloak')->table('user_entity as ue')
            ->selectRaw("ue.id as id, ue.first_name as firstName, ue.last_name as lastName, ue.username, ue.email")
            ->join('user_attribute as ua', 'ue.id', '=', 'ua.user_id')
            ->where('ue.realm_id', config('sso.realm'))
            // Don't add username with value "service-account"
            ->whereRaw('ue.username NOT LIKE ?', "%service-account%");

        if (!empty($search)) {
            $users_query = $users_query->where(function ($query) use ($search) {
                $query->whereRaw('lower(ue.username) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('lower(ue.email) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('lower(ue.first_name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('lower(ue.last_name) LIKE ?', ["%{$search}%"]);
            });
        }

        if (!empty($q)) {
            $users_query = $users_query->where(function ($query) use ($q) {
                foreach ($q as $_q) {
                    foreach ($_q as $key => $value) {
                        $query->orWhere(function ($_query) use ($key, $value) {
                            $_query->where('ua.name', '=', $key)->where('ua.value', '=', $value);
                        });
                    }
                }
            });
        }

        $users_query = $users_query->groupBy('ue.id')->orderBy('ue.created_timestamp');

        if (isset($params['first']) && isset($params['max'])) {
            $users_query = $users_query->offset($params['first'])->limit($params['max']);
        }

        $users_query = $users_query->get();

        $users_raw = collect($users_query)->toArray();
        $users = [];
        foreach ($users_raw as $user_raw) {
            $user_raw = (array) $user_raw;
            $user = [];

            $user_raw['firstName'] = $user_raw['firstname'];
            $user_raw['lastName'] = $user_raw['lastname'];

            $user_attributes_query = DB::connection('keycloak')->table('user_attribute as ua')
            ->selectRaw("ua.name, ua.value")
            ->where('ua.user_id', $user_raw['id'])
            ->whereIn('ua.name', array('nik', 'picture'))
            ->get()
            ->toArray();

            unset($user_raw['firstname'], $user_raw['lastname']);
            $user = array_merge($user_raw, array('attributes' => set_user_attributes($user_attributes_query)));
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Count Keycloak users realm.
     */
    public static function count($params)
    {
        $search = isset($params['search']) ? $params['search'] : '';
        $q = isset($params['q']) ? $params['q'] : [];

        $users_query = DB::connection('keycloak')->table('user_entity as ue')
            ->selectRaw("ue.id as id")
            ->join('user_attribute as ua', 'ue.id', '=', 'ua.user_id')
            ->where('realm_id', config('sso.realm'))
            // Don't add username with value "service-account"
            ->whereRaw('ue.username NOT LIKE ?', "%service-account%");

        if (!empty($search)) {
            $users_query = $users_query->where(function ($query) use ($search) {
                $query->whereRaw('lower(ue.username) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('lower(ue.email) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('lower(ue.first_name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('lower(ue.last_name) LIKE ?', ["%{$search}%"]);
            });
        }

        if (!empty($q)) {
            $users_query = $users_query->where(function ($query) use ($q) {
                foreach ($q as $_q) {
                    foreach ($_q as $key => $value) {
                        $query->orWhere(function ($_query) use ($key, $value) {
                            $_query->where('ua.name', '=', $key)->where('ua.value', '=', $value);
                        });
                    }
                }
            });
        }

        $users_query = $users_query->groupBy('ue.id')->orderBy('ue.created_timestamp')->get();

        return collect($users_query)->count();
    }
}
