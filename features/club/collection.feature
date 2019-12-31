@club @club-collection @club-list
Feature: Show club list
  In order to manage clubs
  As a club admin
  I want to have a club list

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the fixtures "fixtures/club.yml" are loaded
    Given I populate the indexes

  Scenario: List all clubs
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/clubs"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 50 elements
    Then the JSON node "root[0].id" should exist
    Then the JSON node "root[0].name" should exist
    Then the JSON node "root[0].phone" should exist
    Then the JSON node "root[0].email" should exist
    Then the JSON node "root[0].website" should exist
    Then the JSON node "root[0].description" should exist
    Then the JSON node "root[0].description_short" should exist
    Then the JSON node "root[0].booking_information" should exist
    Then the JSON node "root[0].booking_information_short" should exist
    Then the JSON node "root[0].lonlat" should exist
    Then the JSON node "root[0].lonlat.latitude" should exist
    Then the JSON node "root[0].lonlat.longitude" should exist

  Scenario: List all clubs on page 2
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/clubs?page=2"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 11 elements
    Then the JSON node "root[0].id" should exist
    Then the JSON node "root[0].name" should exist
    Then the JSON node "root[0].phone" should exist
    Then the JSON node "root[0].email" should exist
    Then the JSON node "root[0].website" should exist
    Then the JSON node "root[0].description" should exist
    Then the JSON node "root[0].description_short" should exist
    Then the JSON node "root[0].booking_information" should exist
    Then the JSON node "root[0].booking_information_short" should exist
    Then the JSON node "root[0].lonlat" should exist
    Then the JSON node "root[0].lonlat.latitude" should exist
    Then the JSON node "root[0].lonlat.longitude" should exist

  Scenario: List all clubs without records
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/clubs?page=7"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 0 elements
