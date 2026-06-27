<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Utility\CategoryUtility;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryUtilityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_immediate_children()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child1 = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        $child2 = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        Category::factory()->create(['parent_id' => $child1->id, 'level' => 2]);

        $children = CategoryUtility::get_immediate_children($parent->id);
        $this->assertCount(2, $children);
        $this->assertEquals([$child1->id, $child2->id], $children->pluck('id')->toArray());
    }

    public function test_get_immediate_children_ids()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child1 = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        $child2 = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);

        $ids = CategoryUtility::get_immediate_children_ids($parent->id);
        $this->assertCount(2, $ids);
        $this->assertContains($child1->id, $ids);
        $this->assertContains($child2->id, $ids);
    }

    public function test_get_immediate_children_count()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);

        $count = CategoryUtility::get_immediate_children_count($parent->id);
        $this->assertEquals(3, $count);
    }

    public function test_flat_children()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child1 = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        $child2 = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        $grandchild = Category::factory()->create(['parent_id' => $child1->id, 'level' => 2]);

        $flat = CategoryUtility::flat_children($parent->id);
        $flatIds = array_column($flat, 'id');
        $this->assertCount(3, $flat);
        $this->assertContains($child1->id, $flatIds);
        $this->assertContains($child2->id, $flatIds);
        $this->assertContains($grandchild->id, $flatIds);
    }

    public function test_children_ids()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        Category::factory()->create(['parent_id' => $child->id, 'level' => 2]);

        $ids = CategoryUtility::children_ids($parent->id);
        $this->assertCount(2, $ids);
    }

    public function test_category_tree_ids()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child1 = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        $child2 = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        Category::factory()->create(['parent_id' => $child1->id, 'level' => 2]);

        $treeIds = CategoryUtility::category_tree_ids($parent, []);
        $this->assertCount(3, $treeIds);
    }

    public function test_move_children_to_parent()
    {
        $grandParent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $parent = Category::factory()->create(['parent_id' => $grandParent->id, 'level' => 1]);
        $child1 = Category::factory()->create(['parent_id' => $parent->id, 'level' => 2]);
        $child2 = Category::factory()->create(['parent_id' => $parent->id, 'level' => 2]);

        CategoryUtility::move_children_to_parent($parent->id);

        $child1->refresh();
        $child2->refresh();
        $this->assertEquals($grandParent->id, $child1->parent_id);
        $this->assertEquals($grandParent->id, $child2->parent_id);
    }

    public function test_move_level_up()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        $grandchild = Category::factory()->create(['parent_id' => $child->id, 'level' => 2]);

        CategoryUtility::move_level_up($child->id);

        $grandchild->refresh();
        $this->assertEquals(1, $grandchild->level);
    }

    public function test_move_level_down()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);
        $grandchild = Category::factory()->create(['parent_id' => $child->id, 'level' => 2]);

        CategoryUtility::move_level_down($parent->id);

        $child->refresh();
        $this->assertEquals(2, $child->level);
    }

    public function test_update_child_level()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 2]);
        $child = Category::factory()->create(['parent_id' => $parent->id, 'level' => 0]);

        CategoryUtility::update_child_level($parent->id);

        $child->refresh();
        $this->assertEquals(3, $child->level);
    }

    public function test_delete_category()
    {
        $parent = Category::factory()->create(['parent_id' => 0, 'level' => 0]);
        $child = Category::factory()->create(['parent_id' => $parent->id, 'level' => 1]);

        CategoryUtility::delete_category($parent->id);
        $this->assertNull(Category::find($parent->id));
        $child->refresh();
        $this->assertEquals(0, $child->parent_id);
    }
}
