<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Stack;

class StackTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_to_stack() {

        $response = $this->postJson('/api/stack', ['value' => 'Hello']);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Added to stack!']);

    }

    public function test_get_from_stack() {

        $this->postJson('/api/stack', ['value' => 'World']);
        $this->postJson('/api/stack', ['value' => 'Hello']);

        $response = $this->getJson('/api/stack');
        $response->assertStatus(200);
        $response->assertJson(['value' => 'Hello']);

        $this->postJson('/api/stack', ['value' => 'Random']);
        $response = $this->getJson('/api/stack');
        $response->assertStatus(200);
        $response->assertJson(['value' => 'Random']);

        $response = $this->getJson('/api/stack');
        $response->assertStatus(200);
        $response->assertJson(['value' => 'World']);

    }

    public function test_stack_is_empty() {

        $response = $this->getJson('/api/stack');
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Stack is empty']);

    }

}
