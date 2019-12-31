@club @club-update
Feature: Update club
  In order to change club info
  As a club admin
  I want to have possibility to update club

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the fixtures "fixtures/club.yml" are loaded
    Given I populate the indexes

  Scenario: Update club
    And I populate the indexes
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/clubs/1" with body:
    """
    {
      "name": "New club name",
      "git_id": "5161ec75-8c80-48d4-b804-706d6e12ce78",
      "longitude": 45.67,
      "latitude": -15.785069055,
      "phone": "+46766920976",
      "email": "support@club.com",
      "website": "https://club.com",
      "description": "Test description",
      "description_short": "Test short description",
      "booking_information": "Booking information",
      "booking_information_short": "Booking short information",
      "is_sync_with_git": true,
      "is_admin_assure_bookable": true
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
