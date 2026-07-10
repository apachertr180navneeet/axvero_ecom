<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Category;
use Illuminate\Http\Request;

class ReactHomeController extends Controller
{
    /**
     * 1. Trending Now – Men
     * GET /api/v2/home/trending-men
     */
    public function trendingMen(Request $request)
    {
        return $this->sectionProducts('trending_men', $request);
    }

    /**
     * 2. Trending Now – Women
     * GET /api/v2/home/trending-women
     */
    public function trendingWomen(Request $request)
    {
        return $this->sectionProducts('trending_women', $request);
    }

    /**
     * 3. Home Decor
     * GET /api/v2/home/decor
     */
    public function decor(Request $request)
    {
        return $this->sectionProducts('decor', $request);
    }

    /**
     * 4. Featured Footwear
     * GET /api/v2/home/footwear
     */
    public function footwear(Request $request)
    {
        return $this->sectionProducts('footwear', $request);
    }

    /**
     * 5. Hero / Premium categories (categories only, no products)
     * GET /api/v2/home/hero-categories
     */
    public function heroCategories(Request $request)
    {
        return app(CategoryController::class)->home();
    }

    protected function sectionProducts(string $section, Request $request)
    {
        $slug = $this->resolveSectionSlug($section);

        if (!$slug) {
            return response()->json([
                'result' => false,
                'message' => 'Home section category is not configured: ' . $section,
            ], 404);
        }

        return app(ProductController::class)->categoryProducts($slug, $request);
    }

    protected function resolveSectionSlug(string $section): ?string
    {
        $config = config("react_home.sections.{$section}");

        if (!$config) {
            return null;
        }

        if (!empty($config['slug'])) {
            return $config['slug'];
        }

        $index = $config['home_categories_index'] ?? null;
        if ($index !== null) {
            $homeCategoryIds = json_decode(get_setting('home_categories'), true) ?? [];
            $categoryId = $homeCategoryIds[$index] ?? null;

            if ($categoryId) {
                $slug = Category::find($categoryId)?->slug;
                if ($slug) {
                    return $slug;
                }
            }
        }

        if (!empty($config['default_slug'])) {
            return $config['default_slug'];
        }

        return null;
    }
}
