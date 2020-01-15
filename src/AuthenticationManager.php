<?php

declare(strict_types=1);

namespace DrupalTest\BehatOneTimeLogin;

use Drupal\DrupalExtension\Manager\DrupalAuthenticationManager;
use Drupal\user\Entity\User;
use Drupal\user\UserInterface;

/**
 * Authenticates users through the Drupal one time login mechanism.
 *
 * This is based on weitzman/logintrait.
 *
 * @see https://gitlab.com/weitzman/logintrait/
 */
class AuthenticationManager extends DrupalAuthenticationManager
{

    /**
     * {@inheritdoc}
     */
    public function logIn(\stdClass $user): void
    {
        // Ensure we aren't already logged in.
        $this->fastLogout();

        $account = $this->getUnchangedUserByEmail($user->mail);
        if (empty($account)) {
            if (isset($user->role)) {
                throw new \Exception(sprintf(
                    "User '%s' with role '%s' was not found.",
                    $user->name,
                    $user->role
                ));
            } else {
                throw new \Exception(sprintf(
                    "User '%s' was not found.",
                    $user->name
                ));
            }
        }

        $url = $this->getOneTimeLoginUrl($account) . '/login';
        $this->getSession()->visit($url);

        if (!$this->loggedIn()) {
            if (isset($user->role)) {
                throw new \Exception(sprintf(
                    "Unable to determine if logged in because 'log_out' link cannot be found for user '%s' with role " .
                    "'%s'",
                    $user->name,
                    $user->role
                ));
            } else {
                throw new \Exception(sprintf(
                    "Unable to determine if logged in because 'log_out' link cannot be found for user '%s'",
                    $user->name
                ));
            }
        }

        $this->userManager->setCurrentUser($user);
    }

    /**
     * Generates a unique URL for a user to log in and reset their password.
     *
     * The only change here is use of time() instead of REQUEST_TIME.
     *
     * @param \Drupal\user\UserInterface $account
     *   An object containing the user account.
     *
     * @return string
     *   A unique URL that provides a one-time log in for the user, from which
     *   they can change their password.
     */
    protected function getOneTimeLoginUrl(UserInterface $account): string
    {
        $timestamp = time();
        return \Drupal::url(
            'user.reset',
            [
                'uid' => $account->id(),
                'timestamp' => $timestamp,
                'hash' => user_pass_rehash($account, $timestamp),
            ],
            [
                'absolute' => true,
                'language' => \Drupal::languageManager()->getLanguage($account->getPreferredLangcode()),
            ]
        );
    }

    /**
     * Returns the user entity identified by the given email.
     *
     * This will get a fresh copy from the database, bypassing the cache.
     *
     * @param string $email
     *   The email address that matches the desired user entity.
     *
     * @return \Drupal\user\UserInterface|null
     *   The user entity, or NULL if the user is not found in the database.
     *
     * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
     *   Thrown when the user entity plugin definition is invalid.
     * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
     *   Thrown when the user entity type is not defined.
     */
    protected function getUnchangedUserByEmail(string $email): ?UserInterface
    {
        $result = \Drupal::entityQuery('user')
          ->condition('mail', $email)
          ->range(0, 1)
          ->execute();

        if (empty($result)) {
            return null;
        }

        $uid = reset($result);
        return \Drupal::entityTypeManager()->getStorage('user')->loadUnchanged($uid);
    }
}
