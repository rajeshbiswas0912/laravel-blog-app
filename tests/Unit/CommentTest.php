<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_can_be_created(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Test Comment',
        ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'Test Comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_comment_belongs_to_post(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Test Comment',
        ]);

        $this->assertEquals($post->id, $comment->post->id);
    }

    public function test_comment_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Test Comment',
        ]);

        $this->assertEquals($user->id, $comment->user->id);
    }

    public function test_comment_author_can_edit(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Test Comment',
        ]);

        $this->assertTrue($comment->canEdit($user));
    }

    public function test_comment_admin_can_edit(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Test Comment',
        ]);

        $this->assertTrue($comment->canEdit($admin));
    }
}
