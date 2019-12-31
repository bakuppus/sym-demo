@add-promotion @promotion
Feature: Add new promotion to membership
  In order to configure membership
  As a club admin
  I want be able add promotions (rule-sets) to membership

  Background:
    Given I empty the database
    Given the following fixtures files are loaded:
      | fixtures/club.yml       |
      | fixtures/membership.yml |

  Scenario: Added first promotion to membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/1/promotion/new" with body:
    """
    {
      "name": "Promotion number one"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
    Then the JSON node "priority" should contain 0
    Then the JSON node "code" should exist

  Scenario: Added two promotions to membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/1/promotion/new" with body:
    """
    {
      "name": "Promotion number one"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
    Then the JSON node "priority" should contain 0
    Then the JSON node "code" should exist

    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/1/promotion/new" with body:
    """
    {
      "name": "Promotion number two"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
    Then the JSON node "priority" should contain 1
    Then the JSON node "code" should exist
