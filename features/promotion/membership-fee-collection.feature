@membership-fee-collection @membership-fee-list
Feature: Show membership fee list
  In order to manage membership fee
  As a club admin
  I want to have a membership fee list

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml           |
      | fixtures/membership.yml     |
      | fixtures/fee-unit.yml       |
      | fixtures/membership-fee.yml |
    Given I populate the indexes

  Scenario: List all membership fee
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/crm/fees"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 30 elements
    Then the JSON node "root[0].id" should exist
    Then the JSON node "root[0].uuid" should exist
    Then the JSON node "root[0].fee_unit" should exist
    Then the JSON node "root[0].vat" should exist
    Then the JSON node "root[0].price" should exist

  Scenario: Get membership fee record
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/crm/fees/1"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
    Then the JSON node "fee_unit" should exist
    Then the JSON node "vat" should exist
    Then the JSON node "price" should exist

  Scenario: List membership fees without records
    Given I empty the database
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/crm/fees"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 0 elements
