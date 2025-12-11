<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log authenticated users' activities
        if (Auth::check()) {
            try {
                $action = $request->method() . ' ' . $request->path();
                
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => $action,
                    'description' => $this->getActionDescription($request),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            } catch (\Exception $e) {
                // Log the error but don't break the request
                \Log::error('Failed to log activity: ' . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Get a description of the action
     */
    private function getActionDescription(Request $request): string
    {
        $method = $request->method();
        $path = $request->path();

        if (str_contains($path, 'admin')) {
            return "Admin action: {$method} {$path}";
        }

        if ($method === 'POST' && str_contains($path, 'posts')) {
            return "Created a new post";
        }

        if ($method === 'PUT' || $method === 'PATCH') {
            return "Updated resource at {$path}";
        }

        if ($method === 'DELETE') {
            return "Deleted resource at {$path}";
        }

        return "Accessed {$path}";
    }
}
