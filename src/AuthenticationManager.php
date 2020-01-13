<?php

declare(strict_types = 1);

namespace DrupalTest\BehatOneTimeLogin;

use Drupal\DrupalExtension\Manager\DrupalAuthenticationManager;

/**
 * Authenticates users through the Drupal one time login mechanism.
 */
class AuthenticationManager extends DrupalAuthenticationManager {

  /**
   * {@inheritdoc}
   */
  public function logIn(\stdClass $user) {
    var_dump(__METHOD__); ob_flush();
    parent::logIn($user);
  }

}
