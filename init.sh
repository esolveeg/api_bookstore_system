#!/bin/bash

sudo cp .env.example .env

sudo composer install

sudo mysql -u root --password=asd@asd@ esolveeg_systemapi > ./db/esolveeg_systemapi.sql