<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CategoryCollection;
use App\Models\BusinessSetting;
use App\Models\Category;
use Cache;

class CategoryController extends Controller
{

    public function index($parent_id = 0)
    {
        if (request()->has('parent_id') && request()->parent_id) {
            $category = Category::where('slug', request()->parent_id)->first();
            $parent_id = $category->id;
        }

        // return Cache::remember("app.categories-$parent_id", 86400, function () use ($parent_id) {
            return new CategoryCollection(Category::where('parent_id', $parent_id)->whereDigital(0)->get());
        // });
    }

    public function info($slug)
    {
        return new CategoryCollection(Category::where('slug', $slug)->get());
    }

    public function featured()
    {
        return Cache::remember('app.featured_categories', 86400, function () {
            return new CategoryCollection(Category::where('featured', 1)->get());
        });
    }

    public function home()
    {
        if (
            request()->filled('category_id') ||
            request()->filled('slug') ||
            request()->filled('limit') ||
            request()->filled('per_page') ||
            request()->filled('page')
        ) {
            $homeCategoryIds = json_decode(get_setting('home_categories')) ?? [];
            $query = Category::whereIn('id', $homeCategoryIds);

            if (request()->filled('category_id')) {
                $query->where('id', (int) request()->category_id);
            }
            if (request()->filled('slug')) {
                $query->where('slug', request()->slug);
            }
            if (request()->filled('limit')) {
                $query->limit((int) request()->limit);
            }

            if (request()->filled('per_page') || request()->filled('page')) {
                $perPage = max(1, (int) request()->get('per_page', 10));
                return new CategoryCollection($query->paginate($perPage)->appends(request()->query()));
            }

            return new CategoryCollection($query->get());
        }

        return Cache::remember('app.home_categories', 86400, function () {
            $homeCategoryIds = json_decode(get_setting('home_categories')) ?? [];
            return new CategoryCollection(Category::whereIn('id', $homeCategoryIds)->get());
        });
    }

    public function top()
    {
        return Cache::remember('app.top_categories', 86400, function () {
            return new CategoryCollection(Category::whereIn('id', json_decode(get_setting('home_categories')))->limit(20)->get());
        });
    }
}
