<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\User as UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepositoryTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Tests a successful user creation
     *
     * @return void
     */
    public function testCreateUser()
    {
        // User create with all info
        $user = factory(User::class)->make();
        $userArray = $user->makeVisible('password')->toArray();

        $result = UserRepository::save($userArray);
        $this->assertInstanceOf(User::class, $result);

        $userArray['id'] = $result->id;
        $userArray['password'] = $result->password;

        $this->assertDatabaseHas('users', $userArray);


        // User create without birthday
        $user = factory(User::class)->make();
        $userArray = $user->makeVisible('password')->makeHidden('birthday')->toArray();

        $result = UserRepository::save($userArray);
        $this->assertInstanceOf(User::class, $result);

        $userArray['id'] = $result->id;
        $userArray['password'] = $result->password;

        $this->assertDatabaseHas('users', $userArray);
    }

    /**
     * Tests a successful user update
     *
     * @return void
     */
    public function testUpdateUser()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(str_random(15))
        ]);

        // User update without password and birthday
        $newUserInfo = factory(User::class)->make();
        $newUserInfoArray = $newUserInfo->makeHidden('birthday')->toArray();

        $result = UserRepository::save($newUserInfoArray, $user->id);
        $this->assertInstanceOf(User::class, $result);

        $newUserInfoArray['id'] = $user->id;
        $newUserInfoArray['password'] = $user->password;
        $newUserInfoArray['birthday'] = $user->birthday;
        
        $this->assertDatabaseHas('users', $newUserInfoArray);
        

        // User update with all info
        $newUserInfo = factory(User::class)->make();
        $newUserInfoArray = $newUserInfo->makeVisible('password')->toArray();

        $result = UserRepository::save($newUserInfoArray, $user->id);
        $this->assertInstanceOf(User::class, $result);

        $newUserInfoArray['id'] = $user->id;
        $newUserInfoArray['password'] = $result->password;

        $this->assertDatabaseHas('users', $newUserInfoArray);
    }

    /**
     * Tests a unsuccessful user update
     *
     * @return void
     */
    public function testUpdateUserUnsuccessful()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(str_random(15))
        ]);

        $newUserInfo = factory(User::class)->make();
        $newUserInfoArray = $newUserInfo->makeVisible('password')->toArray();

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $result = UserRepository::save($newUserInfoArray, 999);
    }

    /**
     * Tests a successful user delete
     *
     * @return void
     */
    public function testDeleteUser()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(str_random(15))
        ]);

        $userArray = $user->makeVisible('password')->toArray();
        
        $result = UserRepository::delete($user->id);
        $this->assertInstanceOf(User::class, $result);

        $this->assertDatabaseMissing('users', $userArray);
    }

    /**
     * Tests a unsuccessful user delete
     *
     * @return void
     */
    public function testDeleteUserUnsuccessful()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $result = UserRepository::delete(999);
    }
}