#!/bin/bash

php artisan fetch:iamold-keycloak-users;
php artisan fetch:users-candidate;
php artisan store:users-candidate;