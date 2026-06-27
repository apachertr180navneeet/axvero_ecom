<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_category_has_products()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create();
        $category->products()->attach($product->id);

        $this->assertCount(1, $category->products);
        $this->assertTrue($category->products->contains($product));
    }

    public function test_category_children_relationship()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child1 = Category::factory()->child($parent->id, 1)->create();
        $child2 = Category::factory()->child($parent->id, 1)->create();

        $parent->refresh();
        $this->assertCount(2, $parent->childrenCategories);
        $this->assertEquals($child1->id, $parent->childrenCategories->first()->id);
    }

    public function test_category_parent_relationship()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child = Category::factory()->child($parent->id, 1)->create();

        $this->assertEquals($parent->id, $child->parentCategory->id);
    }

    public function test_category_tree_depth()
    {
        $root = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child = Category::factory()->child($root->id, 1)->create();
        $grandchild = Category::factory()->child($child->id, 2)->create();

        $root->refresh();
        $child->refresh();

        $allDescendants = collect();
        foreach ($root->childrenCategories as $c) {
            $allDescendants->push($c);
            foreach ($c->childrenCategories as $gc) {
                $allDescendants->push($gc);
            }
        }

        $this->assertCount(2, $allDescendants);
        $this->assertTrue($allDescendants->contains('id', $child->id));
        $this->assertTrue($allDescendants->contains('id', $grandchild->id));
    }

    public function test_category_has_translations()
    {
        $category = Category::factory()->create();
        $translation = \App\Models\CategoryTranslation::factory()->create([
            'category_id' => $category->id,
            'lang' => 'en',
        ]);

        $this->assertCount(1, $category->category_translations);
        $this->assertEquals($translation->id, $category->category_translations->first()->id);
    }
}
