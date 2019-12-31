@course @course-filter
Feature: Show course list
  According to filters

  Background:
    Given I empty the database
    Given the following fixtures files are loaded:
      | fixtures/club.yml     |
      | fixtures/course.yml   |
      | fixtures/tee-time.yml |
    Given I populate the indexes

  Scenario: Filter courses by distance
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/courses?lonlat%5Blat%5D=59.3296172&lonlat%5Blon%5D=18.0571916&lonlat%5Bdistance%5D=1m"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 1 elements
    Then the JSON node "root[0].id" should exist
    Then the JSON node "root[0].id" should contain 1
    Then the JSON node "root[0].lonlat" should exist
    Then the JSON node "root[0].lonlat.latitude" should exist
    Then the JSON node "root[0].lonlat.longitude" should exist

  Scenario: Order courses by distance
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/courses?order%5Blonlat%5D%5Blat%5D=59.3296172&order%5Blonlat%5D%5Blon%5D=18.0571916&order%5Blonlat%5D%5Bunit%5D=m&order%5Blonlat%5D%5Border%5D=asc"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 50 elements
    Then the JSON node "root[0].id" should exist
    Then the JSON node "root[0].id" should be equal to the number 1
    Then the JSON node "root[0].lonlat" should exist
    Then the JSON node "root[0].lonlat.latitude" should exist
    Then the JSON node "root[0].lonlat.longitude" should exist

  Scenario: Get distance from elastic
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/courses?order%5Blonlat%5D%5Blat%5D=59.3296172&order%5Blonlat%5D%5Blon%5D=18.0571916&order%5Blonlat%5D%5Bunit%5D=m&order%5Blonlat%5D%5Border%5D=asc&calculateDistance[lat]=59.3296172&calculateDistance[lon]=18.0571916"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the JSON node "root" should have 50 elements
    And the JSON node "root[0].id" should exist
    And the JSON node "root[0].id" should be equal to the number 1
    And the JSON node "root[0].distance" should be equal to the number 0
    And the JSON node "root[0].lonlat" should exist
    And the JSON node "root[0].lonlat.latitude" should exist
    And the JSON node "root[0].lonlat.longitude" should exist
    And the JSON node "root[1].distance" should be greater than the number 0

  @skip
  Scenario: Filter if golf-id is required
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/courses?is_git_id_required=false"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the JSON node "root[0].id" should exist
    And the JSON node "root[0].id" should be equal to the number 2

  @skip
  Scenario: Filter if golf-id is not required
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/courses?is_git_id_required=true"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the JSON node "root[0].id" should exist
    And the JSON node "root[0].id" should be equal to the number 1

  @skip
  Scenario: I want to filter courses by range of available slots
    Given I populate the indexes
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/courses?teeTimes.availableSlots[gt]=4"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the JSON node "root[0].id" should exist
    And the JSON node "root[0].id" should be equal to the number 2

  Scenario: I want to filter courses by range of available slots
    Given I populate the indexes
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/courses?teeTimes.availableSlots[lt]=4"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the JSON node "root[0].id" should exist
    And the JSON node "root[0].id" should be equal to the number 3
