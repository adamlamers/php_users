<?php
namespace PHPUsers\Controllers;

use PHPUsers\Models\User;

class UserController
{

    /**
     ** POST
     ** Create a new user
     **/
    public function create()
    {

        if (!isset($_POST['email']) ||
            !isset($_POST['first_name']) ||
            !isset($_POST['last_name']) ||
            !isset($_POST['password'])) {
            $response = [ "status" => "fail", "message" => "Missing required data." ];
            http_response_code(400);
            return json_encode($response);
        }

        $user = new User($_POST['email'], $_POST['first_name'], $_POST['last_name'], $_POST['password']);
        $user->save();

        $response = [ "status" => "ok", "message" => "User Created", "new_user_id" => $user->getId()];
        return json_encode($response);
    }

    /**
     ** GET
     ** Retrieve a user record
     **/
    public function get($id)
    {
        $user = User::load($id);
        if ($user) {
            return $user->json();
        } else {
            $response = [ "status" => "fail",
                "message" => "User does not exist" ];
            http_response_code(404);
            return json_encode($response);
        }
    }

    /**
     ** POST
     ** Update a user record
     **/
    public function update($id)
    {
        $user = User::load($id);

        if ($user) {
            if (isset($_POST['email'])) {
                $user->email = $_POST['email'];
            }

            if (isset($_POST['first_name'])) {
                $user->first_name = $_POST['first_name'];
            }

            if (isset($_POST['last_name'])) {
                $user->last_name = $_POST['last_name'];
            }

            if (isset($_POST['password'])) {
                $user->password = $_POST['password'];
            }

            if ($user->save()) {
                $response = [ "status" => "ok",
                    "message" => "Updated user successfully.",
                    "updated_user_id" => $user->getId() ];
                return json_encode($response);
            } else {
                $response = [ "status" => "fail",
                    "message" => "Failed to update user." ];
                return json_encode($response);
            }
        } else {
            $response = [ "status" => "fail",
                "message" => "User does not exist." ];
            return json_encode($response);
        }
    }

    /**
     ** POST
     ** Remove a user record
     **/
    public function remove($id)
    {
        $user = User::load($id);
        if ($user) {
            if ($user->delete()) {
                $response = [ "status" => "ok",
                    "message" => "Deleted user successfully.",
                    "deleted_user_id" => $user->getId() ];
                return json_encode($response);
            } else {
                $response = [ "status" => "fail",
                    "message" => "Failed to delete user." ];
                return json_encode($response);
            }
        } else {
            $response = [ "status" => "fail",
                "message" => "User does not exist" ];
            http_response_code(404);
            return json_encode($response);
        }
    }
}
