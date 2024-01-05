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
        
        $this->postJson($this->tasks_path, [
            'title' => 'Singularity',
            'description' => 'The concept and the term *singularity* were popularized by Vernor Vinge',
            'due_date' => '2045-01-01',
        ]);

        $this->postJson($this->categories_path, [
            'label' => 'hell',
        ]);
    }

    public function test_index(): void
    {
        $response = $this->get($this->tasks_path);

        $response->assertStatus(200);
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

    public function test_index_after_store(): void
    {
        $response = $this->get($this->tasks_path);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
                    ->first(fn (AssertableJson $json) =>
                        $json->where('id', 4)
                            ->where('title', 'Singularity')
                            ->where('description', 'The concept and the term *singularity* were popularized by Vernor Vinge')
                            ->where('due_date', '2045-01-01 00:00:00')
                            ->etc()
                    )
        );
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
                "data" => [
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
                "data" => [
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
                "data" => [
                    "due_date" => [
                        "The due date must be a valid date"
                    ]
                ]
            ]);
    }

    public function test_delete_success(): void
    {
        $response = $this->deleteJson($this->tasks_path . "9");

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
        $response = $this->get($this->tasks_path . '12');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('task')
                    ->where('task.id', 12)
                    ->where('task.title', 'Singularity')
                    ->where('task.description', 'The concept and the term *singularity* were popularized by Vernor Vinge')
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
        $response = $this->putJson($this->tasks_path . '14', [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('task')
                    ->where('task.id', 14)
                    ->where('task.title', 'go to hell')
                    ->where('task.description', 'welcome to the hell')
                    ->where('task.due_date', '2045-01-01')
                    ->etc()
            );
    }

    public function test_update_success_patch_method(): void
    {
        $response = $this->patchJson($this->tasks_path . '15', [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('task')
                    ->where('task.id', 15)
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
        $response = $this->putJson($this->tasks_path . '17', [
            'title' => '',
            'description' => 'welcome to the hell',
            'due_date' => '2045-01-01',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "data" => [
                    "title" => [
                        "The title is required"
                    ]
                ]
            ]);
    }

    public function test_update_failure_invalid_due_date(): void
    {
        $response = $this->putJson($this->tasks_path . '18', [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '2020',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "data" => [
                    "due_date" => [
                        "The due date must be a valid date"
                    ]
                ]
            ]);
    }

    public function test_update_failure_before_today(): void
    {
        $response = $this->putJson($this->tasks_path . '19', [
            'title' => 'go to hell',
            'description' => 'welcome to the hell',
            'due_date' => '1969-12-31',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "data" => [
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
                $json->has('category')
                    ->where('category.id', 20)
                    ->where('category.label', 'hell')
                    ->etc()
            );
    }
}
