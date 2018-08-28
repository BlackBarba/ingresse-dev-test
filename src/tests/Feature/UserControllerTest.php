<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// $response->assertNotFound();
// $response->assertJsonValidationErrors($keys);

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get users paginated
     *
     * @return void
     */
    public function testGetUserList()
    {
        $response = $this->get(route('users.index'));
        $response->assertOk()
                 ->assertJsonStructure([
                    'current_page',
                    'data',
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total',
                 ])
                 ->assertJson([
                    'current_page' => 1,
                    'per_page' => 15,
                 ]);
    }

    /**
     * Test get one user
     *
     * @return void
     */
    public function testGetUser()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(str_random(15))
        ]);

        $response = $this->get(route('users.show', $user->id));
        $response->assertOk()
                 ->assertJson($user->toArray());
    }

    /**
     * Test get one user that doesn't exists
     *
     * @return void
     */
    public function testGetUserInvalid()
    {
        $response = $this->get(route('users.show', 999));
        $response->assertNotFound()
                 ->assertJsonStructure([
                    'message',
                    'errors'
                 ]);
    }

    /**
     * Test create one user
     *
     * @return void
     */
    public function testCreateUser()
    {
        $user = factory(User::class)->make();

        $response = $this->post(route('users.store'), $user->makeVisible('password')->toArray());
        $response->assertStatus(201)
                 ->assertJsonStructure([
                    'message',
                    'data' => [
                        'name',
                        'username',
                        'email',
                        'birthday',
                        'updated_at',
                        'created_at',
                        'id',
                    ]
                 ])
                 ->assertJson([
                    'data' => $user->makeHidden('password')->toArray()
                 ])
                 ->assertJsonMissing(['password']);

        $this->assertDatabaseHas('users', $user->toArray());
    }

    /**
     * Test create one user with invalida data
     *
     * @return void
     */
    public function testCreateUserInvalid()
    {
        // No data
        $response = $this->post(route('users.store'));
        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'name',
                    'username',
                    'password',
                    'email'
                 ]);

        // Invalid data
        $response = $this->post(route('users.store'), [
            'name' => str_random(256),
            'username' => 'invalid username',
            'email' => 'invalid email',
            'password' => str_random(256),
            'birthday' => 'invalid birthday',
        ]);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'name',
                    'username',
                    'password',
                    'email'
                 ]);

        // Email and username already used
        $user = factory(User::class)->create([
            'password' => Hash::make(str_random(15))
        ]);

        $response = $this->post(route('users.store'), $user->toArray());
        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'email',
                    'username'
                 ]);
    }

    /**
     * Test update one user
     *
     * @return void
     */
    public function testUpdateUser()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(str_random(15))
        ]);
        $newUser = factory(User::class)->make();

        // Update with same data
        $response = $this->put(route('users.update', $user->id), $user->toArray());
        $response->assertOk()
                 ->assertJsonStructure([
                    'message',
                    'data' => [
                        'name',
                        'username',
                        'email',
                        'birthday',
                        'updated_at',
                        'created_at',
                        'id',
                    ]
                 ])
                 ->assertJson([
                    'data' => $user->makeHidden('password')->toArray()
                 ])
                 ->assertJsonMissing(['password']);

        $this->assertDatabaseHas('users', $user->toArray());
        
        // Update all data
        $response = $this->put(route('users.update', $user->id), $newUser->makeVisible('password')->toArray());
        $response->assertOk()
                 ->assertJsonStructure([
                    'message',
                    'data' => [
                        'name',
                        'username',
                        'email',
                        'birthday',
                        'updated_at',
                        'created_at',
                        'id',
                    ]
                 ])
                 ->assertJson([
                    'data' => $newUser->makeHidden('password')->toArray()
                 ])
                 ->assertJsonMissing(['password']);

        $this->assertDatabaseHas('users', $newUser->toArray());
    }

    /**
     * Test update one user with invalida data
     *
     * @return void
     */
    public function testUpdateUserInvalid()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(str_random(15))
        ]);

        // User not found
        $response = $this->put(route('users.update', 999), factory(User::class)->make()->toArray());
        $response->assertNotFound()
                 ->assertJsonStructure([
                    'message',
                    'errors'
                 ]);

        // No data
        $response = $this->put(route('users.update', $user->id));
        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'name',
                    'username',
                    'email'
                 ]);

        // Invalid data
        $response = $this->put(route('users.update', $user->id), [
            'name' => str_random(256),
            'username' => 'invalid username',
            'email' => 'invalid email',
            'password' => str_random(256),
            'birthday' => 'invalid birthday',
        ]);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'name',
                    'username',
                    'password',
                    'email'
                 ]);

        // Email and username already used
        $newUser = factory(User::class)->create([
            'password' => Hash::make(str_random(15))
        ]);

        $user->email = $newUser->email;
        $user->username = $newUser->username;

        $response = $this->put(route('users.update', $user->id), $user->toArray());
        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                    'email',
                    'username'
                 ]);
    }
}