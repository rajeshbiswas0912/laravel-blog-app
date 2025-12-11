<?php

namespace Tests\Unit;

use App\Http\Middleware\CheckRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CheckRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_middleware_allows_admin_access(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin);

        $request = Request::create('/admin', 'GET');
        $middleware = new CheckRole();
        $next = function ($req) {
            return response('OK');
        };

        $response = $middleware->handle($request, $next, 'admin');

        $this->assertEquals('OK', $response->getContent());
    }

    public function test_middleware_blocks_unauthorized_user(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);
        $this->actingAs($user);

        $request = Request::create('/admin', 'GET');
        $middleware = new CheckRole();
        $next = function ($req) {
            return response('OK');
        };

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $middleware->handle($request, $next, 'admin');
    }

    public function test_middleware_redirects_unauthenticated_user(): void
    {
        $request = Request::create('/admin', 'GET');
        $middleware = new CheckRole();
        $next = function ($req) {
            return response('OK');
        };

        $response = $middleware->handle($request, $next, 'admin');

        $this->assertTrue($response->isRedirection());
    }
}
