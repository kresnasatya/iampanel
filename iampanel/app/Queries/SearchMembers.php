<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;
use RistekUSDI\Kisara\User as KisaraUser;

final class SearchMembers {

    public static function getBySQLQuery($params) {
        $search = strtolower($params['search']);
        $key = isset($params['key']) ? $params['key'] : null;
        $value = isset($params['value']) ? $params['value'] : null;

        $members_query = DB::connection('keycloak')->table('keycloak_group as kg')
        ->select('ue.id as id', 'ue.first_name as firstName', 'ue.last_name as lastName', 'ue.username', 'ue.email')
        ->join('user_group_membership as ugm', 'kg.id', '=', 'ugm.group_id')
        ->join('user_entity as ue', 'ue.id', '=', 'ugm.user_id')
        ->join('user_attribute as ua', 'ue.id', '=', 'ua.user_id')
        ->where('kg.id', $params['group_id'])
        ->where('kg.realm_id', config('sso.realm'))
        ->where(function ($query) use ($search) {
            $query->whereRaw('lower(ue.username) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('lower(ue.email) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('lower(ue.first_name) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('lower(ue.last_name) LIKE ?', ["%{$search}%"]);
        });

        if (isset($key) && isset($value)) {
            $members_query->where('ua.name', '=', $key)->where('ua.value', '=', $value);
        }

        $members_query = $members_query
        ->groupBy('ue.id')
        ->offset($params['first'])->limit($params['max'])->get();

        return collect($members_query)->map(function ($member) {
            return json_decode(json_encode($member), true);
        })->toArray();
    }
}
