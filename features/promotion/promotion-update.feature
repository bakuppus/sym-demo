@update-membership @membership
Feature: Update membership
  In order to manage membership
  As a club admin
  I want be able to change general information

  Background:
    Given I empty the database
    Given the following fixtures files are loaded:
      | fixtures/club.yml       |
      | fixtures/membership.yml |
      | fixtures/promotion.yml  |

  Scenario: Update membership name
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/promotions/10" with body:
    """
    {
      "name": "new name"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
    Then the JSON node "name" should contain "new name"
