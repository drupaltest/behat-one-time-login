#!/bin/bash

case "$1" in
    PHP_CodeSniffer)
        cd ${MODULE_DIR}
        ./vendor/bin/phpcs
        exit $?
        ;;
    Behat)
        cd ${MODULE_DIR}
        ./vendor/bin/behat --verbose features/
        exit $?
        ;;
    *)
        echo "Unknown test '$1'"
        exit 1
esac
