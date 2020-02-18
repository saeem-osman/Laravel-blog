<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Cache::tags(['blog-posts'])->flush();
        if($this->command->confirm('Do you want to refresh the database?')){
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed!!');
        }
        $this->call([
            UsersTableSeeder::class,
            BlogPostsTableSeeder::class, 
            CommentsTableSeeder::class
                     ]);

        //  $this->call(UsersTableSeeder::class);
        //  $this->call(BlogPostsTableSeeder::class);
        //  $this->call(CommentsTableSeeder::class);
        
        // DB::table('users')->insert([
        // 'name' => 'Saeem Bhai',
        // 'email' => 'saeem.osman@gmail.com'
        // ]);

    //    dd(count($user));
    //    dd(get_class($doe),get_class($else));
    }
}
