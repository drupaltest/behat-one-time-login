@api
Feature: Log in using the one time login mechanism
  In order to be able to authenticate users in Drupal
  As a business analyst for a project that uses non-standard authentication
  I need to be able to use the one-time login method for logging in

  Scenario: Log in using the one-time login method
    Given I am not logged in
    When I am on the homepage
    Then I should see the text "Log in"
    Given I am logged in as an administrator
    And I am on the homepage
    Then I should not see the text "Log in"
