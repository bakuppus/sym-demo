@club @club-create
Feature: Create new club
  In order to add new club to the system
  As a club admin
  I want to create club

  Scenario: Create new club
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/crm/clubs" with body:
    """
    {
      "name": "Club name",
      "git_id": "5161ec75-8c80-48d4-b804-706d6e12ce78",
      "longitude": 44.44,
      "latitude": -37.505069055,
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
    Then the response status code should be 201
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
