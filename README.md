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
be dropped in and used without requiring changes to existing test scenarios.

Do note that in the standard Drupal login procedure the user ends up on the
homepage after logging in, and with this library they end up on the user profile
page. We do not include a redirection to the homepage since in Behavior Driven
Development the tests are supposed to be readable by business stakeholders. Non-
technical users might not know that Drupal internally redirects to the homepage
after logging in. This means that any tests that are performing actions on the
homepage should have a step telling the user to open the homepage. For example:

```
Given I am logged in as an administrator
And I am on the homepage
```


Development
-----------

Running tests locally:

* Run `composer install` to get the codebase:
  ```bash
  $ composer install
  ```
* Create a customized `behat.yml`:
  ```bash
  $ cp behat.yml.dist behat.yml
  ```
* Adapt the values in `behat.yml` to your needs. You can keep the default Behat
  config and test using the PHP built-in web-server:
  ```bash
  $ ./vendor/bin/drush runserver 8888
  ```
  or use your local web-server if any. In this case, you'll need to set the
  appropriate configurations in `behat.yml`.
* Install Drupal:
  ```bash
  $ ./vendor/bin/drush site-install
  ```
* Run tests:
 ```bash
 $ ./vendor/bin/behat
 ```


 Credits
 -------

 This is based on [weitzman/logintrait](https://gitlab.com/weitzman/logintrait/)
 by Moshe Weitzman.
