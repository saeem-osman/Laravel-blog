<?php

use Illuminate\Database\Seeder;
use App\Tag;
class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 
        $tags = collect(['Science','Politics','Arts','Environment','Public','Entertainment','Technology','Fiction']);
        $tags->each(function($tagName){
            $tag = new Tag();
            $tag->name = $tagName;
            $tag->save();
        });

    }
}
