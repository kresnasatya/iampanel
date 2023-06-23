# IAM Panel

Identity and Access Management Panel with Keycloak. Think it as merge Keycloak Admin Console and Account Console in one place.

## Tools Requirement

- PHP versi >= 7.4
- NodeJS versi 16.
- Laravel 8.x
- [Livewire](https://laravel-livewire.com/)
- [TailwindCSS](https://tailwindcss.com/)
- [AlpineJS](https://alpinejs.dev/)

## How to run this repo

1. Clone repo.
2. Run `cp .env.example .env` and copy env of SSO to file `.env`.
3. Run `composer install`.
4. Run `php artisan key:generate`.
5. Create database named `iampanel`.
6. Run command `php artisan migrate`.
7. Run command `php artisan db:seed`.
8. Run command `php artisan serve --port=8003`.
9. Open another terminal tab.
10. Run command `npm install`.
11. Run command `npm run watch` to watch JS and CSS assets.

## Note

After you successful login, if you see error page 403 Forbidden it means that the user account doesn't have role associated with the client (iampanel). So, you must mapping the user with the role. Iampanel client have 5 roles: Admin, Developer, Mahasiswa, Dosen, and Pegawai. You may choose one of them to mapped to user account.
