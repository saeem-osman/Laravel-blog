<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\BlogPost;
use App\Comment;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function testSeeNoBlogPostWhenThereisNopost(){
        $response = $this->get('/posts');
        $response->assertSeeText('Nothing to show here');
    }
    public function testSee1BlogpostWhenThereIs1Post(){
        //arrange
        $post = new BlogPost();
        $post->title = "This is a new Title";
        $post->content = "This is the content for the post";
        $post->save();
        //act
        $response = $this->get('/posts');
        //assert
        //$response->assertSeeText('No comments yet');
        // $response->assertStatus(200);
        $this->assertDatabaseHas('blogposts',[
            'title'=>'This is a new Title'
        ]);
        
    }
    public function testSee1BlogPostWithComment(){
        $post = $this->createDummyBlogPost();
        factory(Comment::class, 4)->create([
            'blog_post_id'=> $post->id
        ]);
        $response = $this->get('/posts');
        $response->assertSeeText('4 comments');

    }

    
    public function testPostValid(){
        //$user = $this->user();
        $params = [
            'title'=>'New Blog Title',
            'content'=>'This is some content for new post'
        ];
        //$this->actingAs($user);
        $this->actingAs($this->user())
            ->post('/posts',$params)
                ->assertStatus(302)
                    ->assertSessionHas('status');
        $this->assertEquals(session('status'),'A new blog post has been created');

    }
    public function testInvalidPost(){
        $params = [
            'title' => '*',
            'content' => '*'
        ];
        $this->actingAs($this->user())
            ->post('/posts',$params)
                ->assertStatus(302)
                ->assertSessionHas('errors');
        $message = session('errors')->getMessages();
        $this->assertEquals($message['title'][0],'The title must be at least 5 characters.');
        $this->assertEquals($message['content'][0],'The content must be at least 10 characters.');
        // dd($message->getMessages());
    }
    public function testUpdatePost(){
        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        $params = [
            'title'=> 'This is updated title',
            'content'=> 'This is updated content'
        ];
        $this->actingAs($user)
            ->put("/posts/{$post->id}",$params)
                ->assertStatus(302)
                    ->assertSessionHas('status');
        $this->assertEquals(session('status'),'Post has been updated');
        $this->assertDatabaseMissing('blogposts',$post->toArray());
        $this->assertDatabaseHas('blogposts',[
            'title'=>'This is updated title'
        ]);

    }
    public function testDelete(){
        $user = $this->user();
        $post = $this->createDummyBlogPost($user);
        $this->assertDatabaseHas('blogposts',$post->toArray());
        $this->actingAs($user)
            ->delete("/posts/{$post->id}")
            ->assertStatus(302)
                ->assertSessionHas('status');
        $this->assertEquals(session('status'),'Post has been Deleted');
        // $this->assertDatabaseMissing('blogposts',$post->toArray());
        $this->assertSoftDeleted('blogposts', $post->toArray());

    }

    private function createDummyBlogPost($userId = null): BlogPost{
        // $post = new BlogPost();
        // $post->title = 'A new post for testing';
        // $post->content = 'This is the content part of the new post';
        // $post->save();
        // return $post;
        return factory(BlogPost::class)->states('new_title')->create(
            [
                'user_id' => $userId ?? $this->user()->id,
            ]
        );
        
    }
}
