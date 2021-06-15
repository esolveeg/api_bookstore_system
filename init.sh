#!/bin/bash

sudo cp .env.example .env

sudo composer update

sudo chmod -R 775 $(pwd)
sudo chown -R www-data:www-data $(pwd)
sudo mysql -u root --password=asd@asd@ test2 < $(pwd)"/db/esolveeg_systemapi.sql"