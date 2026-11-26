<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactSubmission;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'Pages' => Page::count(),
                'Published pages' => Page::where('status', 'published')->count(),
                'Blog posts' => Post::count(),
                'Media files' => Media::count(),
                'New enquiries' => ContactSubmission::where('status', 'new')->count(),
            ],
            'recent' => ActivityLog::with('user')->latest('created_at')->limit(8)->get(),
        ]);
    }
}
