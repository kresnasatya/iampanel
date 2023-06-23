<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Anonymous\SyncUsers;

use App\Http\Livewire\Group\Index as IndexGroup;
use App\Http\Livewire\Group\Create as CreateGroup;
use App\Http\Livewire\Group\Edit as EditGroup;
use App\Http\Livewire\Group\Members\Index as MembersIndexGroup;
use App\Http\Livewire\Group\Members\Add as AddMembersGroup;
use App\Http\Livewire\Group\AssignRole as AssignRoleGroup;
use App\Http\Livewire\Group\ApplicationMapper\Index as ApplicationMapperGroup;

use App\Http\Livewire\Client\Index as IndexClient;
use App\Http\Livewire\Client\Create as CreateClient;
use App\Http\Livewire\Client\Edit as EditClient;
use App\Http\Livewire\Client\Roles\Index as ClientRoles;
use App\Http\Livewire\Client\Roles\Create as CreateClientRoles;
use App\Http\Livewire\Client\Roles\Edit as EditClientRoles;
use App\Http\Livewire\Client\Roles\Member\Index as IndexMemberClientRoles;
use App\Http\Livewire\Client\Roles\Member\Users\Add as AddUsersMemberClientRoles;
use App\Http\Livewire\Client\Roles\Member\Groups\Add as AddGroupsMemberClientRoles;

use App\Http\Livewire\Client\UserSessions\Index as ClientUserSessions;

use App\Http\Livewire\User\Index as IndexUser;
use App\Http\Livewire\User\View as ViewUser;
use App\Http\Livewire\User\UserSessions\Index as UserSessions;
use App\Http\Livewire\User\AssignRole as AssignRoleUser;

use App\Http\Livewire\UserClients\Index as UserClientsIndex;
use App\Http\Livewire\UserProfile;

use App\Http\Livewire\Realm;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/sso-web.php';
require __DIR__.'/web-session.php';

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/privacy-policy', [WelcomeController::class, 'privacyPolicy'])->name('privacy_policy');

Route::middleware(['imissu-web'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Role Developer
    Route::middleware(['imissu-web.role_active:Developer'])->group(function () {
        Route::get('/sync-users', SyncUsers::class);

        Route::get('/realm', Realm::class)->name('realm');
    });

    Route::name('users.')->group(function () {
        Route::middleware('imissu-web.role_active:Admin|Developer')->group(function () {
            Route::get('/users', IndexUser::class)->name('index');
            Route::get('/users/{user_id}/view', ViewUser::class)->name('view');
            Route::get('/users/{user_id}/user-sessions', UserSessions::class)->name('user-sessions');
            Route::get('/users/{user_id}/assign-role', AssignRoleUser::class)->name('assign-role');
        });
    });

    Route::name('groups.')->group(function () {
        Route::middleware('imissu-web.role_active:Admin|Developer')->group(function () {
            Route::get('/groups', IndexGroup::class)->name('index');
            Route::get('/groups/{group_id}/members', MembersIndexGroup::class)->name('members');
            Route::get('/groups/{group_id}/members/add', AddMembersGroup::class)->name('add.members');
            Route::get('/groups/{group_id}/assign-role', AssignRoleGroup::class)->name('assign-role');
            Route::get('/groups/{group_id}/application-mapper', ApplicationMapperGroup::class)->name('application-mapper');
        });
        Route::middleware('imissu-web.role_active:Developer')->group(function () {
            Route::get('/groups/create', CreateGroup::class)->name('create');
            Route::get('/groups/{group_id}/edit', EditGroup::class)->name('edit');
        });
    });

    Route::name('clients.')->group(function () {
        Route::middleware('imissu-web.role_active:Admin|Developer')->group(function () {
            Route::get('/clients', IndexClient::class)->name('index');
        });
        Route::middleware('imissu-web.role_active:Developer')->group(function () {
            Route::get('/clients/create', CreateClient::class)->name('create');
            Route::get('/clients/{client_id}/edit', EditClient::class)->name('edit');
            Route::get('/clients/{client_id}/roles/create', CreateClientRoles::class)->name('roles.create');
            Route::get('/clients/{client_id}/roles/{role_id}/edit', EditClientRoles::class)->name('roles.edit');
        });
        Route::middleware('imissu-web.role_active:Admin|Developer')->group(function () {
            Route::get('/clients/{client_id}/roles', ClientRoles::class)->name('roles');
            Route::get('/clients/{client_id}/roles/{role_id}/member', IndexMemberClientRoles::class)->name('roles.member');
            Route::get('/clients/{client_id}/roles/{role_id}/member/users/add', AddUsersMemberClientRoles::class)->name('roles.member.users.add');
            Route::get('/clients/{client_id}/roles/{role_id}/member/groups/add', AddGroupsMemberClientRoles::class)->name('roles.member.groups.add');
            Route::get('/clients/{client_id}/user-sessions', ClientUserSessions::class)->name('user-sessions');
        });
    });

    // Role Mahasiswa, Dosen, dan Pegawai
    Route::middleware('imissu-web.role_active:Mahasiswa|Dosen|Pegawai')->group(function () {
        Route::name('user-clients.')->group(function () {
            Route::get('/user-clients', UserClientsIndex::class)->name('index');
        });
    });

    Route::get('/user-profile', UserProfile::class)->name('user-profile');
});
