@membership-fee-delete
Feature: Delete membership fee
  In order to manage membership fee
  As a club admin
  I want to delete a membership fee

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml           |
      | fixtures/membership.yml     |
      | fixtures/fee-unit.yml       |
      | fixtures/membership-fee.yml |
    Given I populate the indexes

  Scenario: Delete membership fee
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "DELETE" request to "/api/crm/fees/1"
    Then the response status code should be 204
    And the response should be empty
