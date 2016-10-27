#!/bin/bash

vagrant ssh -c "cd /vagrant && ./vendor/bin/phinx migrate -e development"
