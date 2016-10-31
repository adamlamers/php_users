<?php
namespace PHPUsers\Models;

use PHPUsers\Config;

class User
{
    public $email;
    public $first_name;
    public $last_name;
    public $password;

    private $id;
    private $exists;
    private $loaded_password;
    protected $table_name = 'users';


    /**
     * User Model Constructor
     *
     * Creates and initializes a new User model object.
     *
     * @param string $email An email to initialize the user object with.
     * @param string $first_name A first name to initialize the user object with.
     * @param string $last_name A last name to initialize the user object with.
     * @param string $password A plain-text password to initialize the user object with. It will be
     * hashed before saving to the database.
     */
    public function __construct($email, $first_name, $last_name, $password)
    {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->password = $password;
        $this->exists = false;
    }

    /**
     * Saves this instance of the User model to the database.
     *
     * Writes the data from this instance of User to the table $this->table_name.
     */
    public function save()
    {
        $mysqli = new \mysqli(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);
        if ($this->exists) {
            if ($this->password != $this->loaded_password) {
                $this->hashPassword();
            }

            $stmt = $mysqli->prepare("UPDATE users SET email=?, first_name=?, last_name=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $this->email, $this->first_name, $this->last_name, $this->password, $this->id);

            $result = $stmt->execute();
            return $result;
        } else {
            $this->hashPassword();

            $stmt = $mysqli->prepare("INSERT INTO users (email, first_name, last_name, password) VALUES(?, ?, ?, ?)");
            $stmt->bind_param("ssss", $this->email, $this->first_name, $this->last_name, $this->password);

            $result = $stmt->execute();
            if ($result) {
                $this->exists = true;
                $this->id = $stmt->insert_id;
            }

            return $result;
        }
    }

    /**
     * Returns the contents of this User encoded in JSON.
     */
    public function json()
    {
        $array = $this->asArray();

        return json_encode($array);
    }

    public function asArray()
    {
        $array = [ "id" => $this->id,
                   "email" => $this->email,
                   "first_name" => $this->first_name,
                   "last_name" => $this->last_name,
                   "password" => $this->password ];

        return $array;
    }

    /**
     * Returns the ID of this User.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Loads a User from the database, with the given $id.
     *
     * @param int $id The ID of the user you wish to load.
     */
    public static function load($id)
    {
        $mysqli = new \mysqli(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

        $stmt = $mysqli->prepare("SELECT id, email, first_name, last_name, password FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($lId, $lEmail, $lFirstName, $lLastName, $lPassword);
        $stmt->execute();

        if ($stmt->fetch()) {
            $user = new User($lEmail, $lFirstName, $lLastName, $lPassword);
            $user->id = $lId;
            $user->exists = true;
            $user->loaded_password = $user->password;
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Deletes this user from the database.
     */
    public function delete()
    {
        $mysqli = new \mysqli(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

        $stmt = $mysqli->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    /**
     * Hashes the $this->password field into a bcrypted password hash.
     */
    private function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
}
