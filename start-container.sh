#!/usr/bin/env sh
set -e

bootstrap() {
    php artisan optimize;
    rr -c .rr.yaml -s serve;
}

bootstrap
