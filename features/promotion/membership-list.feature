@membership-collection @membership-list
Feature: Show membership list
  In order to manage membership
  As a club admin
  I want to have a membership list

  Background:
    Given I empty the database
    Given the following fixtures files are loaded:
      | fixtures/club.yml             |
      | fixtures/membership.yml       |
      | fixtures/promotion.yml        |
      | fixtures/promotion_action.yml |
      | fixtures/promotion_rule.yml   |

  Scenario: List all membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/memberships"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 50 elements
    Then the JSON node "root[0].id" should exist
    Then the JSON node "root[0].uuid" should exist
    Then the JSON node "root[0].name" should exist
    Then the JSON node "root[0].total" should exist
    Then the JSON node "root[0].duration_options" should not be null
    Then the JSON node "root[0].is_active" should exist
    Then the JSON node "root[0].is_git_sync" should exist
    Then the JSON node "root[0].is_hidden" should exist
    Then the JSON node "root[0].play_right_only" should exist
