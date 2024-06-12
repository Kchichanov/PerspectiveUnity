<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\KeyValueStore;
use Carbon\Carbon;

class KeyValueStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_to_store() {

        $response = $this->postJson('/api/key-value', [

            'key' => 'name',
            'value' => 'Arthas',
            'ttl' => 60

        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Key-value pair added']);

        $response = $this->postJson('/api/key-value', [

            'key' => 'name',
            'value' => 'Arthas',
            
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Key-value pair added']);

    }

    public function test_get_value_within_ttl() {

        $this->postJson('/api/key-value', [

            'key' => 'name',
            'value' => 'Arthas',
            'ttl' => 60

        ]);

        $response = $this->getJson('/api/key-value/name');
        $response->assertStatus(200);
        $response->assertJson(['value' => 'Arthas']);

    }

    public function test_get_value_expired() {

        KeyValueStore::create([

            'key' => 'name',
            'value' => 'Arthas',
            'ttl' => Carbon::now()->addSeconds(-1)

        ]);

        $response = $this->getJson('/api/key-value/name');
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Key expired']);

    }

    public function test_delete_value() {

        $this->postJson('/api/key-value', [

            'key' => 'name',
            'value' => 'Arthas',
            'ttl' => 60

        ]);

        $response = $this->deleteJson('/api/key-value/name');
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Key deleted']);

    }

}
