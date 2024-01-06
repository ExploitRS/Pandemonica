<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Testing\Fluent\AssertableJson;

use App\Models\Task;
use App\Models\Category;

class TaskCategoryTest extends TestCase
{
    use RefreshDatabase;

    private $tasks = '/api/todo/tasks/';
    private $categories = '/categories/';

    public function test_index_success(): void
    {
        $task = Task::factory()->create();
        $category = Category::factory()->create();
        $task->categories()->attach($category);

        $response = $this->getJson($this->tasks . $task->id . $this->categories);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has(1)
                ->first(fn (AssertableJson $json) =>
                    $json->where('label', $category->label)
                        ->where('id', $category->id)
                ->etc()
                )
        );
    }

    public function test_index_failure_not_found_task(): void
    {
        $response = $this->getJson($this->tasks . 1 . $this->categories);

        $response->assertStatus(404);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'The task is not found')
        );
    }

    public function test_store_success(): void
    {
        $task = Task::factory()->create();
        $category = Category::factory()->create();

        $response = $this->postJson($this->tasks . $task->id . $this->categories, [
            'category_ids' => [
                ['category_id' => $category->id]
            ]
        ]);

        $response->assertStatus(201);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('label', $category->label)
                ->where('id', $category->id)
            ->etc()
        );
    }

    public function test_store_failure_not_found_task(): void
    {
        $category = Category::factory()->create();

        $response = $this->postJson($this->tasks . 1984 . $this->categories, [
            'category_ids' => [
                ['category_id' => $category->id]
            ]
        ]);

        $response->assertStatus(404);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'The task is not found')
        );
    }

    public function test_store_failure_empty_category_ids(): void
    {
        $task = Task::factory()->create();

        $response = $this->postJson($this->tasks . $task->id . $this->categories, [
            'category_ids' => []
        ]);

        $response->assertStatus(422);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'The given data was invalid.')
                ->where('errors.category_ids', ['The category is required'])
        );
    }

    public function test_store_failure_not_array_category_ids(): void
    {
        $task = Task::factory()->create();

        $response = $this->postJson($this->tasks . $task->id . $this->categories, [
            'category_ids' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'The given data was invalid.')
                ->where('errors.category_ids', ['The category is required'])
        );
    }

    public function test_store_failure_multiple_category(): void
    {
        $task = Task::factory()->create();
        $category = Category::factory()->create();

        $response = $this->postJson($this->tasks . $task->id . $this->categories, [
            'category_ids' => [
                ['category_id' => $category->id],
                ['category_id' => $category->id]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'The given data was invalid.')
                ->where('errors.category_ids', ['The category must contain exactly one element'])
        );
    }

    public function test_store_failure_non_integer_category_id(): void
    {
        $task = Task::factory()->create();

        $response = $this->postJson($this->tasks . $task->id . $this->categories, [
            'category_ids' => [
                ['category_id' => 'not integer']
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'The given data was invalid.')
                ->where('errors.category', ['The category must be an integer'])
        );
    }

    public function test_store_failure_non_positive_category_id(): void
    {
        $task = Task::factory()->create();

        $response = $this->postJson($this->tasks . $task->id . $this->categories, [
            'category_ids' => [
                ['category_id' => -1]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'The given data was invalid.')
                ->where('errors.category', ['The category must be at least 1'])
        );
    }

    public function test_destroy_success(): void
    {
        $task = Task::factory()->create();
        $category = Category::factory()->create();
        $task->categories()->attach($category);

        $response = $this->deleteJson($this->tasks . $task->id . $this->categories . $category->id);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'The category of the task were deleted successfully')
        );
    }

    public function test_destroy_failure_not_found_task(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson($this->tasks . 1984 . $this->categories . $category->id);

        $response->assertStatus(404);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'The task is not found')
        );
    }

    public function test_destroy_failure_not_associated_category(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson($this->tasks . $task->id . $this->categories . 1984);

        $response->assertStatus(404);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'The category is not associated with the task')
        );
    }
}
