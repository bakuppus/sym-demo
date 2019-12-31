@course @course-collection @course-list
Feature: Show course list
  In order to manage courses
  As a course admin
  I want to have a course list


  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml   |
      | fixtures/course.yml |
    Given I populate the indexes

  Scenario: List all courses
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/courses"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 50 elements
    Then the JSON node "root[0].id" should exist
    Then the JSON node "root[0].name" should exist
    Then the JSON node "root[0].description" should exist

    Then the JSON node "root[0].club" should exist
    Then the JSON node "root[0].club.id" should exist
    Then the JSON node "root[0].club.uuid" should exist
    Then the JSON node "root[0].club.name" should exist

    Then the JSON node "root[0].lonlat" should exist
    Then the JSON node "root[0].lonlat.latitude" should exist
    Then the JSON node "root[0].lonlat.longitude" should exist

  Scenario: List all courses on page 2
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/courses?page=2"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 40 elements
    Then the JSON node "root[0].id" should exist
    Then the JSON node "root[0].name" should exist
    Then the JSON node "root[0].description" should exist

    Then the JSON node "root[0].club" should exist
    Then the JSON node "root[0].club.id" should exist
    Then the JSON node "root[0].club.uuid" should exist
    Then the JSON node "root[0].club.name" should exist

    Then the JSON node "root[0].lonlat" should exist
    Then the JSON node "root[0].lonlat.latitude" should exist
    Then the JSON node "root[0].lonlat.longitude" should exist

  Scenario: List all courses on page without records
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/courses?page=10"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 0 elements
