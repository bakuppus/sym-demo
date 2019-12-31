@promotion @promotion-action @add-new-action-to-promotion
Feature: Add new action to promotion
  In order to add new actions to promotion
  As a club admin
  I want to set actions in a rule sets

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml       |
      | fixtures/membership.yml |
      | fixtures/promotion.yml  |
    Given I populate the indexes

  Scenario: Add new greenfee guest discount successfully
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/promotions/1/action/new" with body:
    """
    {
      "type": "greenfee_guest_percentage_discount",
      "configuration": {
         "percentage_coefficient": 0.5
      }
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "type" should exist
    Then the JSON node "configuration" should exist

  Scenario: Add new greenfee member discount wrongly
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/promotions/1/action/new" with body:
    """
    {
      "type": "greenfee_member_percentage_discount",
      "configuration": {
         "percentage_coefficient": 1
      }
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "type" should exist
    Then the JSON node "configuration" should exist

  Scenario: Try to add wrong action
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/promotions/1/action/new" with body:
    """
    {
      "type": "wrong_action_type",
      "configuration": {
         "some_key": true
      }
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/problem+json; charset=utf-8"
    Then the JSON node "violations" should exist

  Scenario: Try to add rule checker
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/promotions/1/action/new" with body:
    """
    {
     "type": "days_in_week_checker",
     "configuration": ["Monday", "Friday"]
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/problem+json; charset=utf-8"
    Then the JSON node "violations" should exist
