@fee-unit @fee-unit-list
Feature: Show fee unit list
  In order to show display in Membership Wizard
  As a club admin
  I want to have a fee unit list

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/fee-unit.yml |
    Given I populate the indexes

  Scenario: List all fee units
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/fee-units"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 4 elements
    Then the JSON node "root[0].id" should exist
    Then the JSON node "root[0].name" should exist
    Then the JSON node "root[0].uuid" should exist

  Scenario: Get fee unit record
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/fee-units/1"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "name" should exist
    Then the JSON node "uuid" should exist

  Scenario: List fee units without records
    Given I empty the database
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/fee-units"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 0 elements
