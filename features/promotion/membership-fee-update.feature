@membership-fee-update
Feature: Update membership fee
  In order to manage membership fee
  As a club admin
  I want to update a membership fee

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml           |
      | fixtures/membership.yml     |
      | fixtures/fee-unit.yml       |
      | fixtures/membership-fee.yml |
    Given I populate the indexes

  Scenario: Update membership fee
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/fees/1" with body:
    """
    {
      "vat": 10,
      "price": 100
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
    Then the JSON node "vat" should exist
    Then the JSON node "price" should exist
