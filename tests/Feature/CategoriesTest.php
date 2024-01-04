<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    private $path = "/api/todo/categories/";

    protected function setUp(): void
    {
        parent::setUp();

        $this->postJson($this->path, [
            "label" => "hell",
        ]);
    }

    public function test_index(): void
    {
        $response = $this->get($this->path);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
                    ->first(fn (AssertableJson $json) =>
                        $json->where("id", 1)
                            ->where("label", "hell")
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
            ->assertJson([
                "label" => "hell",
            ]);
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
                "data" => [
                    "label" => [
                        "The label is required",
                    ],
                ],
            ]);
    }

    public function test_update_success(): void
    {
        $response = $this->putJson($this->path . "5", [
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
        $response = $this->putJson($this->path . "6", [
            "label" => "",
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "data" => [
                    "label" => [
                        "The label is required",
                    ],
                ],
            ]);
    }

    public function test_delete_success(): void
    {
        $response = $this->deleteJson($this->path . "7");

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
        $response = $this->get($this->path . "9");

        $response
            ->assertStatus(200)
            ->assertJson([
                "id" => 9,
                "label" => "hell",
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
