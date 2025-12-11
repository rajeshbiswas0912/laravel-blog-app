<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display admin dashboard with statistics
     */
    public function index()
    {
        $stats = Cache::remember('admin.stats', 300, function () {
            return [
                'total_posts' => Post::count(),
                'total_users' => User::count(),
                'total_comments' => Comment::count(),
                'recent_posts' => Post::with('user')->latest()->take(5)->get(),
                'recent_users' => User::latest()->take(5)->get(),
            ];
        });

        return view('admin.dashboard', compact('stats'));
    }
}
