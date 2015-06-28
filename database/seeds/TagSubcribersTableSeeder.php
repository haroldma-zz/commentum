<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TagSubscriber;

class TagSubscribersTableSeeder extends Seeder
{
    public function run()
    {
        foreach (User::all() as $user)
        {
			$tagSubscriber          = new TagSubscriber;
			$tagSubscriber->tag_id  = 1;
			$tagSubscriber->user_id = $user->id;
			$tagSubscriber->save();

			$tagSubscriber          = new TagSubscriber;
			$tagSubscriber->tag_id  = 2;
			$tagSubscriber->user_id = $user->id;
			$tagSubscriber->save();

			$tagSubscriber          = new TagSubscriber;
			$tagSubscriber->tag_id  = 3;
			$tagSubscriber->user_id = $user->id;
			$tagSubscriber->save();
        }
    }
}
