# PHP User Management

## Running in a development environment

### Setup

Create vagrant box
    `vagrant up`
Install composer packages
    `composer install`
Run migrations
    `tools/vagrant_migrate.sh`
Run tests
    `tools/vagrant_test.sh`

### Tests

Tests assume an empty database. If you're running a dirty dev instance, clear the database with

    `vagrant ssh -c "cd /vagrant && ./vendor/bin/phinx migrate -e development -t 0"`
Then, run
    `tools/vagrant_migrate.sh`
And the tests should work.
    `tools/vagrant_test.sh`

### Usage

The app is set up as a JSON api, so calling with POST data will be the easiest way to interact with
it. As an example, here are some CURL command examples to call the endpoints.

#### Create a user

    `curl --data "email=test&first_name=test&last_name=test&password=test" http://testbox.dev/user/create`

#### Retrieve a user

    `curl http://testbox.dev/user/3/get`

#### Modify a user

    `curl --data "email=test2" http://testbox.dev/user/3/update" http://testbox.dev/user/3/update`

#### Delete a user

    `curl http://testbox.dev/user/3/delete`