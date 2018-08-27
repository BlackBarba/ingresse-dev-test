<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\User as UserRepository;
use App\Models\User;

class UserRepositoryTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Tests a successful user creation
     *
     * @return void
     */
    public function testCreateUserSuccessful()
    {
        $user = factory(User::class)->make();
        $userArray = $user->makeVisible('password')->toArray();

        $result = UserRepository::save($userArray);
        $this->assertInstanceOf(User::class, $result);

        $userArray['id'] = $result->id;
        $userArray['password'] = $result->password;

        $this->assertDatabaseHas('users', $userArray);
    }
}