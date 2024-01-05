<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

use App\Models\Task;
use App\Models\Category;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    private $tasks_path = '/api/todo/tasks/';
    private $categories_path = '/api/todo/categories/';

    protected function setUp(): void
    {
        parent::setUp();
        
        // $this->postJson($this->tasks_path, [
        //     'title' => 'Singularity',
        //     'description' => 'The concept and the term *singularity* were popularized by Vernor Vinge',
        //     'due_date' => '2045-01-01',
        // ]);

        // $this->postJson($this->categories_path, [
        //     'label' => 'hell',
        // ]);
    }

    public function test_index(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $response = $this->get($this->tasks_path);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
                    ->first(fn (AssertableJson $json) =>
                        $json->where('id', $task->id)
                            ->where('title', $task->title)
                            ->where('description', $task->description)
                            ->where('due_date', '2045-01-01 00:00:00')
                            ->where('is_done', $task->is_done)
                            ->etc()
                    )
            );
    }

    public function test_store_success(): void
    {
        $response = $this->postJson($this->tasks_path, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-10-10',
        ]);

        $response->assertStatus(201);
    }

    public function test_store_failure_due_date(): void
    {
        $response = $this->post($this->tasks_path, [
            'title' => 'Test task',
            'description' => 'Test description',
            'due_date' => '2020-10-10',
        ]);

        $response->assertStatus(422);
    }

    public function test_store_failure_empty_title(): void
    {
        $response = $this->post($this->tasks_path, [
            'title' => '',
            'description' => 'Test description',
            'due_date' => '2045-10-10',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "title" => [
                        "The title is required"
                    ]
                ]
            ]);
    }

    public function test_store_failure_max_title(): void
    {
        $response = $this->post($this->tasks_path, [
            'title' => 'Lorem ipsum dolor sit amet, consectetu adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue.',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "title" => [
                        "The title cannot be longer than 255 characters"
                    ]
                ]
            ]);

    }

    public function test_store_failure_invalid_due_date(): void
    {
        $response = $this->post($this->tasks_path, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2020',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "due_date" => [
                        "The due date must be a valid date"
                    ]
                ]
            ]);
    }

    public function test_delete_success(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);
        $response = $this->deleteJson($this->tasks_path . $task->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                "message" => "Task deleted successfully",
            ]);
    }

    public function test_delete_failure_deleted(): void
    {
        $response = $this->deleteJson($this->tasks_path . "1");

        $response
            ->assertStatus(404)
            ->assertJson([
                "message" => "The given data was invalid.",
            ]);
    }

    public function test_delete_failure_not_found(): void
    {
        $response = $this->deleteJson($this->tasks_path . "100");

        $response
            ->assertStatus(404)
            ->assertJson([
                "message" => "The given data was invalid.",
            ]);
    }

    public function test_show_success(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);
        $response = $this->get($this->tasks_path . $task->id);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('task')
                    ->where('task.id', $task->id)
                    ->where('task.title', $task->title)
                    ->where('task.description', $task->description)
                    ->where('task.due_date', '2045-01-01 00:00:00')
                ->has('category')
                    ->where('category', null)
                    ->etc()
            );
    }

    public function test_show_failure_not_found(): void
    {
        $response = $this->get($this->tasks_path . '1');

        $response
            ->assertStatus(404)
            ->assertJson([
                "message" => "The given data was invalid.",
            ]);
    }

    public function test_update_success(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $response = $this->putJson($this->tasks_path . $task->id, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('task')
                    ->where('task.id', $task->id)
                    ->where('task.title', 'go to hell')
                    ->where('task.description', 'welcome to the hell')
                    ->where('task.due_date', '2045-01-01')
                    ->etc()
            );
    }

    public function test_update_success_patch_method(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $response = $this->patchJson($this->tasks_path . $task->id, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('task')
                    ->where('task.id', $task->id)
                    ->where('task.title', 'go to hell')
                    ->where('task.description', 'welcome to the hell')
                    ->where('task.due_date', '2045-01-01')
                    ->etc()
            );
    }

    public function test_update_failure_not_found(): void
    {
        $response = $this->putJson($this->tasks_path . '1', [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
        ]);

        $response
            ->assertStatus(404)
            ->assertJson([
                "message" => "The given data was invalid.",
            ]);
    }

    public function test_update_failure_empty_title(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $response = $this->putJson($this->tasks_path . $task->id, [
            'title' => '',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "title" => [
                        "The title is required"
                    ]
                ]
            ]);
    }

    public function test_update_failure_invalid_due_date(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $response = $this->putJson($this->tasks_path . $task->id, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2020',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "due_date" => [
                        "The due date must be a valid date"
                    ]
                ]
            ]);
    }

    public function test_update_failure_before_today(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $response = $this->putJson($this->tasks_path . $task->id, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '1969-12-31',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "due_date" => [
                        "The due date must be today or later"
                    ]
                ]
            ]);
    }

    public function test_store_success_with_category(): void
    {
        $category = Category::factory()->create([
            'label' => 'hell',
        ]);

        $response = $this->postJson($this->tasks_path, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
            'category_ids' => [
                ['category_id' => $category->id],
            ],
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('task')
                    ->where('task.title', 'go to hell')
                    ->where('task.description', 'welcome to the hell')
                    ->where('task.due_date', '2045-01-01')
                ->has('category')
                    ->where('category.id', $category->id)
                    ->where('category.label', 'hell')
                    ->etc()
            );
    }

    public function test_store_failure_not_found_category(): void
    {
        $response = $this->postJson($this->tasks_path, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
            'category_ids' => [
                ['category_id' => 100],
            ],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', "The given data was invalid.")
                    ->has('errors')
                        ->where('errors.category', ["The category must exist in the database"])
            );
    }

    public function test_store_failure_empty_category_ids(): void
    {
        $response = $this->postJson($this->tasks_path, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
            'category_ids' => [],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', "The given data was invalid.")
                    ->has('errors')
                        ->where('errors.category_ids', ["The category is required"])
            );
    }

    public function test_store_failure_multiple_category(): void
    {
        $response = $this->postJson($this->tasks_path, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
            'category_ids' => [
                ['category_id' => 1],
                ['category_id' => 2],
            ],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', "The given data was invalid.")
                    ->has('errors')
                        ->where('errors.category_ids', ["The category must contain exactly one element"])
            );
    }

    public function test_store_failure_less_than_one_category(): void
    {
        $response = $this->postJson($this->tasks_path, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
            'category_ids' => [
                ['category_id' => 0],
            ],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', "The given data was invalid.")
                    ->has('errors')
                        ->where('errors.category', ["The category must be at least 1"])
            );
    }

    public function test_store_failure_string_category(): void
    {
        $response = $this->postJson($this->tasks_path, [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
            'category_ids' => [
                ['category_id' => 'hell'],
            ],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', "The given data was invalid.")
                    ->has('errors')
                        ->where('errors.category', ["The category must be an integer"])
            );
    }

    public function test_update_success_category(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $category = Category::factory()->create([
            'label' => 'hell',
        ]);

        $response = $this->putJson($this->tasks_path . $task->id, [
            'category_ids' => [
                ['category_id' => $category->id],
            ],
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('task')
                    ->where('task.id', $task->id)
                    ->where('task.title', $task->title)
                    ->where('task.description', $task->description)
                    ->where('task.due_date', '2045-01-01 00:00:00')
                    ->where('task.is_done', $task->is_done)
                ->has('category')
                    ->where('category.id', $category->id)
                    ->where('category.label', 'hell')
                ->etc()
            );
    }

    public function test_update_failure_empty_category_ids(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $response = $this->putJson($this->tasks_path . $task->id, [
            'category_ids' => [],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', "The given data was invalid.")
                    ->has('errors')
                        ->where('errors.category_ids', ["The category is required"])
            );
    }

    public function test_update_failure_multiple_category(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $cat01 = Category::factory()->create([
            'label' => 'hell',
        ]);

        $cat02 = Category::factory()->create([
            'label' => 'heaven',
        ]);

        $response = $this->putJson($this->tasks_path . $task->id, [
            'category_ids' => [
                ['category_id' => $cat01->id],
                ['category_id' => $cat02->id],
            ],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', "The given data was invalid.")
                    ->has('errors')
                        ->where('errors.category_ids', ["The category must contain exactly one element"])
            );
    }

    public function test_update_failure_not_found_category(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $response = $this->putJson($this->tasks_path . $task->id, [
            'category_ids' => [
                ['category_id' => 100],
            ],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', "The given data was invalid.")
                    ->has('errors')
                        ->where('errors.category', ["The category must exist in the database"])
            );
    }

    public function test_update_failure_non_integer_category(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $response = $this->putJson($this->tasks_path . $task->id, [
            'category_ids' => [
                ['category_id' => 'hell'],
            ],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', "The given data was invalid.")
                    ->has('errors')
                        ->where('errors.category', ["The category must be an integer"])
            );
    }

    public function test_update_failure_negative_category_id(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2045-01-01',
        ]);

        $response = $this->putJson($this->tasks_path . $task->id, [
            'category_ids' => [
                ['category_id' => -1],
            ],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('message', "The given data was invalid.")
                    ->has('errors')
                        ->where('errors.category', ["The category must be at least 1"])
            );
    }
}
