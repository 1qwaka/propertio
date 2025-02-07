#!/usr/bin/env bash

/usr/local/bin/node_exporter &

php artisan octane:start --host=0.0.0.0
