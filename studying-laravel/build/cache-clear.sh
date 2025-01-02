#!/bin/bash

cd $(dirname $0)/..

usage() {
    echo "Usage $0 {soft, hard, force}"
    exit
}

cache-clear-soft() {
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
}

cache-clear-hard() {
    composer dump-autoload
    php artisan clear-compiled
    php artisan optimize
}

cache-clear-force() {
    php artisan key:generate
    find storage/framework/ -type f -name "schedule-*" | xargs --no-run-if-empty rm
    find bootstrap/cache -type f -name "config.php" | xargs --no-run-if-empty rm
}

if [ $# -lt 1 ]; then
    usage
fi

if [ "$1" = "soft" ]; then
    cache-clear-soft
elif [ "$1" = "hard" ]; then
    cache-clear-soft
    cache-clear-hard
elif [ "$1" = "force" ]; then
    cache-clear-soft
    cache-clear-hard
    cache-clear-force
else
    usage
fi
