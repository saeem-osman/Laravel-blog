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
        $users = App\User::all();
        if($posts->count()<1 || $users->count() <1){
            $this->command->info('There is no blog posts or users. So no comment will be added');
            return;
        }
        $commentCount = (int)$this->command->ask('How many comments? ', 150);
        factory(App\Comment::class, $commentCount)->make()->each(function($comment) use($posts,$users){
            $comment->user_id = $users->random()->id;
            $comment->commentable_id = $posts->random()->id;
            $comment->commentable_type = 'App\BlogPost';
            $comment->save();
        });

        factory(App\Comment::class, $commentCount)->make()->each(function($comment) use($users){
            $comment->commentable_id = $users->random()->id;
            $comment->commentable_type = 'App\User';
            $comment->user_id = $users->random()->id;
            $comment->save();
        });
    }
}
