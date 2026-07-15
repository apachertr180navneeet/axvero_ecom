<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'title' => $data->title,
                    'slug' => $data->slug,
                    'short_description' => $data->short_description,
                    'description' => $data->description,
                    'banner' => uploaded_asset($data->banner),
                    'meta_title' => $data->meta_title,
                    'meta_description' => $data->meta_description,
                    'status' => $data->status,
                    'category' => $data->category?->category_name,
                ];
            })
        ];
    }

    public function with($request)
    {
        $response = [
            'success' => true,
            'status' => 200,
        ];

        if ($this->resource instanceof LengthAwarePaginator) {
            $response['pagination'] = [
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'per_page' => $this->resource->perPage(),
                'total' => $this->resource->total(),
            ];
        }

        return $response;
    }
}
