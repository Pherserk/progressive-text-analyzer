#!/usr/bin/env bash

composer update
./vendor/bin/phpunit -c . 
