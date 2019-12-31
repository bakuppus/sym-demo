@membership-fee-create
Feature: Create membership fee
  In order to have new membership fee
  As a club admin
  I want to create a membership fee

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml       |
      | fixtures/membership.yml |
      | fixtures/fee-unit.yml   |
    Given I populate the indexes

  Scenario: Create membership fee
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/1/fees/new" with body:
    """
    {
      "fee_unit": 1,
      "vat": 10,
      "price": 100
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
