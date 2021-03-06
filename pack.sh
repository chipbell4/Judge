#!/bin/bash

# first, remove any stored files that don't need to be copied
rm -f site.tgz
rm -f app/database/production.sqlite

for folder in judging_input judging_output logs sessions solution_code views; do
    rm -f app/storage/$folder/*
done

# Re install composer to only include non-dev packages
composer install --no-dev

# now tar everything up
files="VERSION app/*.php app/commands app/config app/database app/Judge app/lang app/library app/start app/storage app/views bootstrap public vendor artisan"
tar czvf site.tgz $files

# Go back to development mode
composer install
