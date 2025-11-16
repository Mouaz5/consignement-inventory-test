#!/usr/bin/env bash

# This script is a convenience wrapper for ./vendor/bin/sail
# It allows you to run: ./sail <command> instead of ./vendor/bin/sail <command>

if [ ! -f vendor/bin/sail ]; then
    echo "Error: Sail is not installed. Run 'composer install' first."
    exit 1
fi

exec ./vendor/bin/sail "$@"
