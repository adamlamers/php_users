#!/bin/bash
vagrant ssh -c "cd /vagrant && ./vendor/bin/phpunit --bootstrap vendor/autoload.php --whitelist models/*.php --whitelist controllers/*.php --coverage-text=coverage_report.txt tests"
