@promotion @promotion-action @add-new-action-to-promotion
Feature: Update promotion action
  In order to manage promotion actions
  As a club admin
  I want to update promotion action

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml             |
      | fixtures/membership.yml       |
      | fixtures/promotion.yml        |
      | fixtures/promotion_action.yml |
    Given I populate the indexes

  Scenario: Update action
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/promotions/action/2" with body:
    """
    {
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
