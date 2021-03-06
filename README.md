# PHP User Management

## Running in a development environment

### Setup

Create vagrant box

    vagrant up

Edit hosts

    echo 192.168.59.76   testbox.dev www.testbox.dev | sudo tee -a /etc/hosts

Install composer packages

    composer install

Run migrations

    tools/vagrant_migrate.sh

Run tests

    tools/vagrant_test.sh

### Tests

Tests assume an empty database. If you're running a dirty dev instance, clear the database with

    vagrant ssh -c "cd /vagrant && ./vendor/bin/phinx migrate -e development -t 0"
Then, run

    tools/vagrant_migrate.sh

And the tests should work:

    tools/vagrant_test.sh

### Usage

The app is set up as a JSON api, so calling with POST data will be the easiest way to interact with
it. As an example, here are some CURL command examples to call the endpoints.

#### Create a user

    curl -X POST --data "email=test@test.test&first_name=test&last_name=test&password=test" http://testbox.dev/user

#### Retrieve a user

    curl http://testbox.dev/user/3

#### Retrieve a user by email
    curl http://testbox.dev/user/test@test.test

#### Modify a user

    curl -X POST --data "email=test2@test.test" http://testbox.dev/user/3

#### Delete a user

    curl -X DELETE http://testbox.dev/user/3

### Configuration

Config lives in the project root, in `config.php`

### Explanation

This project uses a custom routing system, to facilitate using RESTful style URIs.
It provides a JSON-style API for creating, reading, updating and deleting User accounts in
a MySQL database. I thought it would be fun to create my own routing system, and it works
alright for the use case this project presented. It's a Regex based system so sometimes
clever methods of matching the URI are needed.
