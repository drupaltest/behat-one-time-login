Behat One Time Login
====================

Authenticates users through the Drupal one time login mechanism in Behat
scenarios.

This is intended for projects that do not use the default Drupal login form but
instead rely on 2-Factor Authentication, Single Sign On or other non-standard
authentication methods.


Installation
------------

Add the library to your development dependencies:

```
$ composer require --dev drupaltest/behat-one-time-login
```

Add the following to your `behat.yml` configuration file:

```
default:
  extensions:
    FriendsOfBehat\ServiceContainerExtension:
      imports:
        - "./vendor/drupaltest/behat-one-time-login/behat.services.yml"
```


Usage
-----

This transparently replaces the standard login method, so in most cases it can
be dropped in and used without requiring changes to existing test scenarios. Do
note that in the standard Drupal login procedure the user ends up on the
homepage after logging in, and with this library they end up on the user profile
page. Any tests that expect to be on the homepage after logging in should have
the following line added after the step that logs in the user:

```
Given I am on the homepage
```

