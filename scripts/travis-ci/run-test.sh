#!/bin/bash

cd ${TRAVIS_BUILD_DIR}
case "$1" in
    PHP_CodeSniffer)
        ./vendor/bin/phpcs
        exit $?
        ;;
    Behat)
        ./vendor/bin/behat --verbose features/
        exit $?
        ;;
    *)
        echo "Unknown test '$1'"
        exit 1
esac
