<?php

return [

    /*
    |--------------------------------------------------------------------------
    | React home page section → category mapping
    |--------------------------------------------------------------------------
    |
    | Each section resolves a category slug used by products/category/{slug}.
    | Set REACT_HOME_*_SLUG in .env to pin a slug explicitly. When unset, the
    | category at home_categories_index from admin home_categories is used.
    |
    */

    'sections' => [
        'trending_men' => [
            'slug' => env('REACT_HOME_MEN_SLUG'),
            'home_categories_index' => 0,
            'default_slug' => "men's fashion",
        ],
        'trending_women' => [
            'slug' => env('REACT_HOME_WOMEN_SLUG'),
            'home_categories_index' => 1,
            'default_slug' => "women's fashion",
        ],
        'decor' => [
            'slug' => env('REACT_HOME_DECOR_SLUG'),
            'home_categories_index' => 2,
            'default_slug' => 'home-decor--furniture-ofmpd',
        ],
        'footwear' => [
            'slug' => env('REACT_HOME_FOOTWEAR_SLUG'),
            'home_categories_index' => 3,
            'default_slug' => 'Footwear-GQnF7',
        ],
    ],
];
