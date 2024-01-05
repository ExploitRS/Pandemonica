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
}
