@promotion @promotion-rule @add-new-rule-to-promotion
Feature: Add new rule to promotion
  In order to add new rules to promotion
  As a club admin
  I want to set rules in a rule sets

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml       |
      | fixtures/membership.yml |
      | fixtures/promotion.yml  |
    Given I populate the indexes

  Scenario: Add new "days_in_week_checker" rule
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/promotions/1/rule/new" with body:
    """
    {
     "type": "days_in_week_checker",
     "configuration": ["Monday", "Friday"]
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "type" should exist
    Then the JSON node "configuration" should exist

  Scenario: Add new "included_courses_checker" rule
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/promotions/1/rule/new" with body:
    """
    {
	    "type": "included_courses_checker",
	    "configuration": [
          10,
          11,
          12
	    ]
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "type" should exist
    Then the JSON node "configuration" should exist

  Scenario: Add new "number_of_simultaneous_bookings_checker" rule
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/promotions/1/rule/new" with body:
    """
    {
	    "type": "number_of_simultaneous_bookings_checker",
	    "configuration": {
            "number_of_simultaneous_bookings": 10
	    }
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "type" should exist
    Then the JSON node "configuration" should exist

  Scenario: Add new "number_of_rounds_limitation_checker" rule
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/promotions/1/rule/new" with body:
    """
    {
	    "type": "number_of_rounds_limitation_checker",
	    "configuration": {
            "limitation_value": 10
	    }
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "type" should exist
    Then the JSON node "configuration" should exist

  Scenario: Add new "play_value_limitation_checker" rule
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/promotions/1/rule/new" with body:
    """
    {
	    "type": "play_value_limitation_checker",
	    "configuration": {
            "limitation_value": 10
	    }
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "type" should exist
    Then the JSON node "configuration" should exist
