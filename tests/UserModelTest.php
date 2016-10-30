<?php
namespace PHPUsers\Tests;

use PHPUnit\Framework\TestCase;
use PHPUsers\Models\User;

class UserModelTests extends TestCase
{

    /**
     * Test that creating a user with the User model works.
     */
    public function testCreateUser()
    {
        $user = new User('test', 'test_first', 'test_last', 'test_pass');
        $user->save();

        $this->assertEquals($user->email, 'test');
        $this->assertEquals($user->first_name, 'test_first');
        $this->assertEquals($user->last_name, 'test_last');
        $this->assertEquals(true, password_verify('test_pass', $user->password));

        return $user->getId();
    }

    public function testUserAsJSON()
    {
        $user = new User('test', 'test_first', 'test_last', 'test_pass');

        $user_array = array('id' => null,
                     'email' => 'test',
                     'first_name' => 'test_first',
                     'last_name' => 'test_last',
                     'password' => 'test_pass');

        $this->assertEquals($user->json(), json_encode($user_array));
    }

    /**
     * @depends testCreateUser
     */
    public function testLoadUser($created_id)
    {
        $user = User::load($created_id);

        $this->assertNotNull($user);

        $this->assertEquals($user->getId(), $created_id);
        $this->assertEquals($user->first_name, 'test_first');
        $this->assertEquals($user->last_name, 'test_last');
        $this->assertEquals(true, password_verify('test_pass', $user->password));
        return $created_id;
    }

    /**
     * @depends testLoadUser
     */
    public function testDeleteUser($created_id)
    {
        $user = User::load($created_id);
        $this->assertEquals(true, $user->delete());
    }

}
