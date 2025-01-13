<?php

namespace Tests\Feature\Staff;

use App\Models\User;
use App\Models\Child;
use App\Models\Nursery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChildControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $staffUser;
    private Nursery $nursery;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a nursery
        $this->nursery = Nursery::create([
            'name' => 'Test Nursery',
            'email' => 'test@nursery.com',
            'phone' => '1234567890',
            'address' => '123 Test St',
        ]);

        // Create a staff user
        $this->staffUser = User::create([
            'name' => 'Test Staff',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'type' => 'staff',
            'is_admin' => true,
            'nursery_id' => $this->nursery->id,
        ]);
    }

    public function test_staff_can_create_child(): void
    {
        $this->actingAs($this->staffUser);

        $childData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => '2020-01-01',
            'gender' => 'male',
            'notes' => 'Test notes',
        ];

        $response = $this->postJson('/api/staff/children', $childData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'child' => [
                    'id',
                    'first_name',
                    'last_name',
                    'date_of_birth',
                    'gender',
                    'notes',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('children', [
            'first_name' => $childData['first_name'],
            'last_name' => $childData['last_name'],
            'nursery_id' => $this->nursery->id,
        ]);
    }

    public function test_staff_cannot_create_child_with_invalid_data(): void
    {
        $this->actingAs($this->staffUser);

        $response = $this->postJson('/api/staff/children', [
            'first_name' => '', // Invalid: empty first name
            'last_name' => $this->faker->lastName,
            'date_of_birth' => 'invalid-date', // Invalid date format
            'gender' => 'invalid', // Invalid gender option
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'date_of_birth', 'gender']);
    }

    public function test_non_staff_cannot_create_child(): void
    {
        // Create a non-staff user
        $nonStaffUser = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => bcrypt('password'),
            'type' => 'parent',
            'nursery_id' => $this->nursery->id,
        ]);

        $this->actingAs($nonStaffUser);

        $childData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => '2020-01-01',
            'gender' => 'male',
            'notes' => 'Test notes',
        ];

        $response = $this->postJson('/api/staff/children', $childData);

        $response->assertStatus(403);
    }

    public function test_staff_can_only_see_children_from_their_nursery(): void
    {
        $this->actingAs($this->staffUser);

        // Create a child in staff's nursery
        $childInNursery = Child::create([
            'nursery_id' => $this->nursery->id,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => '2020-01-01',
            'gender' => 'male',
        ]);

        // Create another nursery and child
        $otherNursery = Nursery::create([
            'name' => 'Other Nursery',
            'email' => 'other@nursery.com',
            'phone' => '0987654321',
            'address' => '456 Other St',
        ]);

        $childInOtherNursery = Child::create([
            'nursery_id' => $otherNursery->id,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => '2020-01-01',
            'gender' => 'male',
        ]);

        // Test index endpoint
        $response = $this->getJson('/api/staff/children');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $childInNursery->id])
            ->assertJsonMissing(['id' => $childInOtherNursery->id]);

        // Test show endpoint for child in nursery
        $response = $this->getJson("/api/staff/children/{$childInNursery->id}");
        $response->assertStatus(200);

        // Test show endpoint for child in other nursery
        $response = $this->getJson("/api/staff/children/{$childInOtherNursery->id}");
        $response->assertStatus(403);
    }
}
