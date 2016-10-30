<?php
namespace PHPUsers\Tests;

use PHPUnit\Framework\TestCase;
use PHPUsers\Controllers\UserController;

class UserControllerTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();
        $_POST = array();
    }

    /**
     * Test that a valid payload creates a user successfully.
     */
    public function testCreateUserSucceeds()
    {
        $controller = new UserController();

        $_POST['email'] = 'unit_test_email';
        $_POST['first_name'] = 'php';
        $_POST['last_name'] = 'unit';
        $_POST['password'] = 'phpunit';
        $return = json_decode($controller->create());

        $this->assertEquals($return->status, 'ok');
        $this->assertEquals($return->message, 'User Created');
        return $return->new_user_id;
    }

    /**
     * Test that an invalid payload returns the correct error
     */
    public function testCreateUserFail()
    {
        $controller = new UserController();

        $_POST['email'] = 'unit_test_email';
        $_POST['first_name'] = 'php';
        $_POST['last_name'] = 'unit';
        $return = json_decode($controller->create());

        $this->assertEquals($return->status, 'fail');
        $this->assertEquals($return->message, 'Missing required data.');
        $this->assertEquals(http_response_code(), 400);
    }

    /**
     * @depends testCreateUserSucceeds
     */
    public function testGetUserSucceeds($created_user_id)
    {
        $controller = new UserController();

        $return = json_decode($controller->get($created_user_id));

        $this->assertEquals($return->status, 'ok');
        $this->assertEquals($return->message, 'Retrieved user successfully.');

        $this->assertEquals($return->user->email, 'unit_test_email');
        $this->assertEquals($return->user->first_name, 'php');
        $this->assertEquals($return->user->last_name, 'unit');
        $this->assertEquals(true, password_verify('phpunit', $return->user->password));

        return $created_user_id;
    }

    /**
     * Test that getting a user fails with an impossible ID.
     */
    public function testGetUserFail()
    {
        $controller = new UserController();

        $return = json_decode($controller->get(-1));

        $this->assertEquals($return->status, 'fail');
    }

    /**
     * Test that updating a user works.
     * @depends testGetUserSucceeds
     */

    public function testUpdateUserSucceeds($created_user_id)
    {
        $controller = new UserController();

        $_POST['email'] = 'unit_test_email_updated';
        $_POST['first_name'] = 'php_u';
        $_POST['last_name'] = 'unit_u';
        $_POST['password'] = 'phpunit_u';

        $return = json_decode($controller->update($created_user_id));

        $this->assertEquals($return->status, 'ok');
        $this->assertEquals($return->message, 'Updated user successfully.');
        $this->assertEquals($return->updated_user_id, $created_user_id);

        return $return->updated_user_id;
    }

    /**
     * Test that updating a user fails.
     */
    public function testUpdateUserFails()
    {
        $controller = new UserController();
        $return = json_decode($controller->update(-1));

        $this->assertEquals($return->status, 'fail');
        $this->assertEquals($return->message, 'User does not exist.');
    }

    /**
     * Test that deleting a user succeeds.
     * @depends testUpdateUserSucceeds
     */
    public function testRemoveUserSucceeds($created_user_id)
    {
        $controller = new UserController();
        $return = json_decode($controller->remove($created_user_id));

        $this->assertEquals($return->status, 'ok');
        $this->assertEquals($return->message, 'Deleted user successfully.');
    }

    /**
     * Test that deleting a user fails with an impossible ID.
     */
    public function testRemoveUserFails()
    {
        $controller = new UserController();
        $return = json_decode($controller->remove(-1));

        $this->assertEquals($return->status, 'fail');
        $this->assertEquals($return->message, 'User does not exist.');
    }
}
