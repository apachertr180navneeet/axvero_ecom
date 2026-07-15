<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\BlogCollection;
use App\Http\Resources\V2\BlogResource;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogFrontendController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::query()->where('status', 1);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                foreach (explode(' ', trim($search)) as $word) {
                    $q->where('title', 'like', "%{$word}%")
                        ->orWhere('short_description', 'like', "%{$word}%");
                }
            });

            $case1 = $search . '%';
            $case2 = '%' . $search . '%';
            $query->orderByRaw("CASE 
                WHEN title LIKE '{$case1}' THEN 1 
                WHEN title LIKE '{$case2}' THEN 2 
                ELSE 3 
                END");
        }

        if ($request->filled('selected_categories')) {
            $categoryIds = BlogCategory::whereIn('slug', $request->selected_categories)
                ->pluck('id')
                ->toArray();
            $query->whereIn('category_id', $categoryIds);
        }

        $perPage = max(1, (int) $request->get('per_page', 12));
        $blogs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $recentBlogs = Blog::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();

        return response()->json([
            'result' => true,
            'blogs' => new BlogCollection($blogs),
            'selected_categories' => $request->get('selected_categories', []),
            'search' => $request->get('search'),
            'recent_blogs' => BlogResource::collection($recentBlogs),
        ]);
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json([
                'result' => false,
                'message' => 'Blog not found',
            ], 404);
        }

        $recentBlogs = Blog::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();

        return response()->json([
            'result' => true,
            'blog' => new BlogResource($blog),
            'recent_blogs' => BlogResource::collection($recentBlogs),
        ]);
    }
}
