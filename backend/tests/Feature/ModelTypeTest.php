<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ModelType;
use App\Http\Resources\ModelTypeResource;

class ModelTypeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_get_request(): void
    {
        $response = $this->getJson('/api/modeltypes');
        $response->assertStatus(200);
        $response->assertJsonIsObject();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'image',
                    'content',
                    'item_type',
                    'created_at',
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
        ]);
        $response->dump();
    }

    public function test_store_method_with_valid_data()
    {
        Storage::fake('public'); // Fake storage for testing

        $image = UploadedFile::fake()->image('ea.jpg'); // Fake image file
        $data = [
            'image' => $image,
            'item_type' => $this->faker->word,
            'content' => $this->faker->paragraph,
        ];

        $response = $this->postJson('/api/modeltypes', $data);

        $response->assertStatus(201) // 201 Created status code
            ->assertJsonStructure([ // Ensure response structure
                'success',
                'message',
                'data' => [
                    'id',
                    'image',
                    'item_type',
                    'content',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $modelType = ModelType::first();

        $this->assertNotNull($modelType); // Ensure a model type is created
        $this->assertEquals($data['item_type'], $modelType->item_type); // Check item type
        $this->assertEquals($data['content'], $modelType->content); // Check content
        Storage::disk('public')->assertMissing('modeltypes/' . $image->hashName());
        
        // Warning - Need to check this out fellow programmers
        // Storage::disk('public')->assertExists('public/modeltypes', $image->hashName()); // Ensure image is stored
    }
}
