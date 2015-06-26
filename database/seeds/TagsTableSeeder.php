<?php

use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\TagMod;

class TagsTableSeeder extends Seeder
{
    public function run()
    {
        $i = 1;

        $titles = ['', 'random', 'blog', 'announcements'];
        $descriptions = [
        	'',
        	'The official random tag.',
        	'The blog tag of Commentum.',
        	'The official announcements tag.'
        ];

        while ($i < 4)
        {
            $tag                = new Tag;
            $tag->title         = $titles[$i];
            $tag->display_title = $titles[$i];
            $tag->description   = $descriptions[$i];
            $tag->owner_id      = 1;
            $tag->momentum      = 9000;
			$tag->save();

            $tagMod          = new TagMod;
            $tagMod->user_id = 1;
            $tagMod->tag_id  = $tag->id;
            $tagMod->save();

            $i++;
        }
    }
}
