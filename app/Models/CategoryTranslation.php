<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryTranslation extends Model
{
    use HasFactory, PreventDemoModeChanges;

    protected $fillable = ['name', 'lang', 'category_id'];

    public function category(){
    	return $this->belongsTo(Category::class);
    }
}
