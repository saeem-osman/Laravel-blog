<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = App\BlogPost::all();
        if($posts->count()<1){
            $this->command->info('There is no blog posts. So no comment will be added');
            return;
        }
        $commentCount = (int)$this->command->ask('How many comments? ', 150);
        factory(App\Comment::class, $commentCount)->make()->each(function($comment) use($posts){
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
