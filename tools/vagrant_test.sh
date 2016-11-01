#!/bin/bash
vagrant ssh -c "cd /vagrant && ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests"
