@club @membership @add-membership-to-club
Feature: Add new membership to club
  In order to add new membership to club
  As a club admin
  I want to set general information about the membership

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the fixtures "fixtures/club.yml" are loaded
    Given I populate the indexes

  Scenario: Add new membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/clubs/5/memberships/new" with body:
    """
    {
      "name": "Gold Membership",
      "duration_options": ["12_month"],
      "is_hidden": false,
      "is_active": true,
      "play_right_only": false
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist