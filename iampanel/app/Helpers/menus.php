<?php

if (! function_exists('load_role_menus')) {
    function load_role_menus()
    {
        return [
            'Developer' => [
                [
                    'name' => 'Master Pengguna',
                    'icon' => 'users',
                    'route' => 'users.index',
                    'route_name' => 'users.'
                ],
                [
                    'name' => 'Master Kelompok',
                    'icon' => 'user-group',
                    'route' => 'groups.index',
                    'route_name' => 'groups.'
                ],
                [
                    'name' => 'Master Aplikasi',
                    'icon' => 'clipboard-list',
                    'route' => 'clients.index',
                    'route_name' => 'clients.'
                ],
            ],

            'Admin' => [
                [
                    'name' => 'Master Pengguna',
                    'icon' => 'users',
                    'route' => 'users.index',
                    'route_name' => 'users.'
                ],
                [
                    'name' => 'Master Kelompok',
                    'icon' => 'user-group',
                    'route' => 'groups.index',
                    'route_name' => 'groups.'
                ],
                [
                    'name' => 'Master Aplikasi',
                    'icon' => 'clipboard-list',
                    'route' => 'clients.index',
                    'route_name' => 'clients.'
                ],
            ],

            'Mahasiswa' => [
                [
                    'name' => 'Akses Aplikasi',
                    'icon' => 'clipboard-list',
                    'route' => 'user-clients.index',
                    'route_name' => 'user-clients.'
                ],
            ],

            'Dosen' => [
                [
                    'name' => 'Akses Aplikasi',
                    'icon' => 'clipboard-list',
                    'route' => 'user-clients.index',
                    'route_name' => 'user-clients.'
                ],
            ],

            'Pegawai' => [
                [
                    'name' => 'Akses Aplikasi',
                    'icon' => 'clipboard-list',
                    'route' => 'user-clients.index',
                    'route_name' => 'user-clients.'
                ],
            ],
        ];
    }
}
