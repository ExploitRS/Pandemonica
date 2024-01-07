<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

use App\Models\Category;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    private $path = "/api/todo/categories/";

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_index(): void
    {
        $category = Category::factory()->create();

        $response = $this->get($this->path);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
                    ->first(fn (AssertableJson $json) =>
                        $json->where("id", $category->id)
                            ->where("label", $category->label)
                        ->etc()
                    )
            );
    }
    
    public function test_store_success(): void
    {
        $response = $this->postJson($this->path, [
            "label" => "hell",
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where("label", "hell")
                ->etc()
            );
    }

    public function test_store_failure_empty_label(): void
    {
        $response = $this->postJson($this->path, [
            "label" => "",
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "label" => [
                        "The label is required",
                    ],
                ],
            ]);
    }

    public function test_update_success(): void
    {
        $category = Category::factory()->create();

        $response = $this->putJson($this->path . $category->id, [
            "label" => "malicious",
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                "label" => "malicious",
            ]);
    }

    public function test_update_failure_empty_label(): void
    {
        $category = Category::factory()->create();

        $response = $this->putJson($this->path . $category->id, [
            "label" => "",
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "label" => [
                        "The label is required",
                    ],
                ],
            ]);
    }

    public function test_delete_success(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson($this->path . $category->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                "message" => "Category deleted successfully",
            ]);
    }

    public function test_delete_failure(): void
    {
        $response = $this->deleteJson($this->path . "1");

        $response
            ->assertStatus(404)
            ->assertJson([
                "message" => "The given data was invalid.",
            ]);
    }

    public function test_show_success(): void
    {
        $category = Category::factory()->create();

        $response = $this->get($this->path . $category->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                "id" => $category->id,
                "label" => $category->label,
            ]);
    }

    public function test_show_failure(): void
    {
        $response = $this->get($this->path . "1");

        $response
            ->assertStatus(404)
            ->assertJson([
                "message" => "The given data was invalid.",
            ]);
    }
}
