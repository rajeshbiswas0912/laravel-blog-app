<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_can_be_created(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'user_id' => $user->id,
        ]);
    }

    public function test_post_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $post->user->id);
        $this->assertEquals($user->name, $post->user->name);
    }

    public function test_post_can_have_comments(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $comment = $post->comments()->create([
            'user_id' => $user->id,
            'content' => 'Test Comment',
        ]);

        $this->assertTrue($post->comments->contains($comment));
        $this->assertEquals(1, $post->comments->count());
    }

    public function test_post_can_be_soft_deleted(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $post->delete();

        $this->assertSoftDeleted('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_post_author_can_edit(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $this->assertTrue($post->canEdit($user));
    }

    public function test_post_admin_can_edit(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user->id,
        ]);

        $this->assertTrue($post->canEdit($admin));
    }

    public function test_post_other_user_cannot_edit(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::create([
            'title' => 'Test Post',
            'content' => 'Test Content',
            'user_id' => $user1->id,
        ]);

        $this->assertFalse($post->canEdit($user2));
    }
}
