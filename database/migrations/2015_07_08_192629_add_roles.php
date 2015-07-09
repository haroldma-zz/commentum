<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;

class AddRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'Administrator';
        $admin->description  = 'Has administrative rights over content.';
        $admin->save();

        $supermod = new Role();
        $supermod->name         = 'supermod';
        $supermod->display_name = 'Super Mod';
        $supermod->description  = 'Can moderate all communes.';
        $supermod->save();

        $user = new Role();
        $user->name         = 'user';
        $user->display_name = 'Normal user';
        $user->description  = 'Can create tags, posts and comments.';
        $user->save();

        $users = User::where('username', 'noodles_ftw')
            ->orWhere('username', 'capn_krunk')
            ->orWhere('username', 'harry')->get();

        foreach ($users as $adminUser){
            $adminUser->attachRole($admin);
        }

        $users = User::where('username', '!=', 'noodles_ftw')
            ->where('username', '!=', 'capn_krunk')
            ->where('username', '!=', 'harry')->get();

        foreach ($users as $normalUser){
            $normalUser->attachRole($user);
        }

        $flagContent = new Permission();
        $flagContent->name         = 'flag-content';
        $flagContent->display_name = 'Flag Content';
        $flagContent->description  = 'flag a thread or comment';
        $flagContent->save();

        $createPost = new Permission();
        $createPost->name         = 'create-thread';
        $createPost->display_name = 'Create Threads';
        $createPost->description  = 'create new threads';
        $createPost->save();

        $createCommunes = new Permission();
        $createCommunes->name         = 'create-commune';
        $createCommunes->display_name = 'Create Communes';
        $createCommunes->description  = 'create new communes';
        $createCommunes->save();

        $createComments = new Permission();
        $createComments->name         = 'create-comment';
        $createComments->display_name = 'Create Comments';
        $createComments->description  = 'create new comments';
        $createComments->save();

        $removeThreads = new Permission();
        $removeThreads->name         = 'remove-thread';
        $removeThreads->display_name = 'Remove Threads';
        $removeThreads->description  = 'remove any thread';
        $removeThreads->save();

        $editThreads = new Permission();
        $editThreads->name         = 'edit-thread';
        $editThreads->display_name = 'Edit Threads';
        $editThreads->description  = 'edit any thread';
        $editThreads->save();

        $removeComments = new Permission();
        $removeComments->name         = 'remove-comment';
        $removeComments->display_name = 'Remove Comments';
        $removeComments->description  = 'remove any comment';
        $removeComments->save();

        $editComments = new Permission();
        $editComments->name         = 'edit-comment';
        $editComments->display_name = 'Edit Comments';
        $editComments->description  = 'edit any comment';
        $editComments->save();

        $banUserFromCommune = new Permission();
        $banUserFromCommune->name         = 'ban-user-from-commune';
        $banUserFromCommune->display_name = 'Ban User from Commune';
        $banUserFromCommune->description  = 'ban any user from a commune';
        $banUserFromCommune->save();

        $banUser = new Permission();
        $banUser->name         = 'ban-user';
        $banUser->display_name = 'Ban User';
        $banUser->description  = 'ban any user site-wide';
        $banUser->save();

        $makeDefault = new Permission();
        $makeDefault->name         = 'make-default';
        $makeDefault->display_name = 'Make Communes Defaults';
        $makeDefault->description  = 'make any commune a default';
        $makeDefault->save();

        $removeCommune = new Permission();
        $removeCommune->name         = 'remove-commune';
        $removeCommune->display_name = 'Remove Communes';
        $removeCommune->description  = 'remove any commune';
        $removeCommune->save();

        $permissions = array($createPost, $createCommunes, $createComments, $flagContent);
        foreach ($permissions as $permission) {
            $user->attachPermission($permission);
        }

        $permissions[] = $removeThreads;
        $permissions[] = $removeComments;
        $permissions[] = $banUserFromCommune;
        foreach ($permissions as $permission) {
            $supermod->attachPermission($permission);
        }

        $permissions[] = $editThreads;
        $permissions[] = $editComments;
        $permissions[] = $banUser;
        $permissions[] = $makeDefault;
        $permissions[] = $removeCommune;
        foreach ($permissions as $permission) {
            $admin->attachPermission($permission);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
