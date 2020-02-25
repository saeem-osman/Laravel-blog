<?php

use Illuminate\Database\Seeder;
use App\Tag;
use App\BlogPost;

class BlogPostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tagCount = Tag::all()->count();
        if(0 === $tagCount){
            $this->command->info('No tag found, no assiging with tags');
            return;
        }

        $howManyMin = (int)$this->command->ask('How many minimum tags? ', 0);
        $howManyMax = min((int)$this->command->ask('How many maximum tags? ',$tagCount),$tagCount);

        BlogPost::all()->each(function(BlogPost $post)use($howManyMax,$howManyMin){
            $take = random_int($howManyMin,$howManyMax);
            $tags = Tag::inRandomOrder()->take($take)->get()->pluck('id');
            $post->tags()->sync($tags);
        });
    } 
}
