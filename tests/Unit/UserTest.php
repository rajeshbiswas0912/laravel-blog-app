<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => User::ROLE_USER,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => User::ROLE_USER,
        ]);
    }

    public function test_user_can_have_admin_role(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertEquals(User::ROLE_ADMIN, $admin->role);
    }

    public function test_user_can_have_regular_role(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $this->assertFalse($user->isAdmin());
        $this->assertEquals(User::ROLE_USER, $user->role);
    }

    public function test_user_can_have_posts(): void
    {
        $user = User::factory()->create();
        $post = $user->posts()->create([
            'title' => 'Test Post',
            'content' => 'Test Content',
        ]);

        $this->assertTrue($user->posts->contains($post));
        $this->assertEquals(1, $user->posts->count());
    }

    public function test_user_can_have_comments(): void
    {
        $user = User::factory()->create();
        $post = \App\Models\Post::factory()->create(['user_id' => $user->id]);
        $comment = $post->comments()->create([
            'user_id' => $user->id,
            'content' => 'Test Comment',
        ]);

        $this->assertTrue($user->comments->contains($comment));
        $this->assertEquals(1, $user->comments->count());
    }
}
