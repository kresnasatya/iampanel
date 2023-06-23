<?php

function get_user_types()
{
    return array(
        '1' => 'Mahasiswa',
        '2' => 'Dosen',
        '3' => 'Pegawai'
    );
}

function search_user_type($type)
{
    $user_types = get_user_types();
    $user_type = 'Unknown';
    if (is_array($type)) {
        return $user_type;
    } else {
        foreach ($user_types as $key => $value) {
            if ($key === (int) $type) {
                $user_type = $value;
            }
        }
        return $user_type;
    }
}

function glob_search($str)
{
    $str_arr = explode(' ', trim(strtolower($str)));

    $new_str_arr = [];
    foreach ($str_arr as $s) {
        $new_str_arr[] = "*{$s}*";
    }

    return implode(" ", $new_str_arr);
}

// Get categories of a client app
function get_client_categories()
{
    return [
        '#akademik',
        '#alumni',
        '#kemahasiswaan',
        '#keuangan',
        '#sdm',
        '#umum',
    ];
}

function user_profile_picture($path = null)
{
    return url('photos/default.jpg');
}

// Note: q stands for query
if (! function_exists('q_to_array')) {
    function q_to_array($q)
    {
        $result = [];
        $arr_q = explode(" ", $q);
        foreach ($arr_q as $key => $value) {
            $split_kv = explode(":", $value);
            $result[] = array($split_kv[0] => $split_kv[1]);
        }

        return $result;
    }
}

if (! function_exists('set_user_attributes')) {
    function set_user_attributes($user_attributes)
    {
        $attributes = [];
        foreach ($user_attributes as $user_attribute) {
            $user_attribute = collect($user_attribute)->toArray();
            $attributes[$user_attribute['name']] = array($user_attribute['value']);
        }

        return $attributes;
    }
}

// Get query format for user_type_id attribute from Keycloak
if (! function_exists('get_q_user_type_id')) {
    function get_q_user_type_id()
    {
        return array(
            'user_type_id:1' => 'Mahasiswa',
            'user_type_id:2' => 'Dosen',
            'user_type_id:3' => 'Pegawai'
        );
    }
}
