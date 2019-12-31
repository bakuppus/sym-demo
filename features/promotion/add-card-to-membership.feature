@membership-card-create
Feature: Add player to membership
  In order to add player to membership
  As a club admin
  I want to have player in membership

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml           |
      | fixtures/player.yml         |
      | fixtures/membership.yml     |
      | fixtures/fee-unit.yml       |
      | fixtures/membership-fee.yml |
      | fixtures/play-right.yml     |
    Given I populate the indexes

  Scenario: Add 12 month membership to player
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/21/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "12_month",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
    Then the JSON node "expires_at" should exist
    Then the JSON node "starts_at" should exist
    Then the JSON node "player" should exist
    Then the JSON node "membership" should exist
    Then the JSON node "club" should exist
    Then the JSON node "status" should contain "future"
    Then the JSON node "state" should contain "new"

  Scenario: Add annual duration membership to player
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/21/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "annual_duration",
      "calendar_year": "2020-01-01",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    Then the JSON node "uuid" should exist
    Then the JSON node "expires_at" should exist
    Then the JSON node "starts_at" should exist
    Then the JSON node "player" should exist
    Then the JSON node "membership" should exist
    Then the JSON node "club" should exist
    Then the JSON node "status" should contain "future"
    Then the JSON node "state" should contain "new"

  Scenario: Add membership to a player with wrong duration type
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/22/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "annual_duration",
      "calendar_year": "2020-01-01",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/problem+json; charset=utf-8"
    Then the JSON node "violations" should exist
    And the JSON node "violations" should have 1 element
    And the JSON node "violations[0].propertyPath" should contain "duration_type"
    And the JSON node "violations[0].message" should contain "Invalid duration type"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/23/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "12_month",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/problem+json; charset=utf-8"
    Then the JSON node "violations" should exist
    And the JSON node "violations" should have 1 element
    And the JSON node "violations[0].propertyPath" should contain "duration_type"
    And the JSON node "violations[0].message" should contain "Invalid duration type"

  Scenario: Try to add 12 month duration type with calendar_year to membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/22/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "12_month",
      "calendar_year": "2019-01-01",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/problem+json; charset=utf-8"
    Then the JSON node "violations" should exist
    And the JSON node "violations" should have 1 element
    And the JSON node "violations[0].propertyPath" should contain "calendar_year"
    And the JSON node "violations[0].message" should contain "This option is available only for annual duration"

  Scenario: Try to add annual duration card without year to membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/23/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "annual_duration",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/problem+json; charset=utf-8"
    Then the JSON node "violations" should exist
    And the JSON node "violations" should have 1 element
    And the JSON node "violations[0].propertyPath" should contain "duration_type"
    And the JSON node "violations[0].message" should contain "Annual duration requires calendar_year field"

  Scenario: Try to add player with wrong calendar year to membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/23/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "annual_duration",
      "calendar_year": "2018-01-01",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/problem+json; charset=utf-8"
    Then the JSON node "violations" should exist
    And the JSON node "violations[0].propertyPath" should contain "calendar_year"
    And the JSON node "violations[0].message" should contain "Invalid year. You can add membership to either current or next year only"

  Scenario: Try to add player to a draft and active membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/2/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "12_month",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/problem+json; charset=utf-8"
    And the JSON node "violations" should have 1 element
    And the JSON node "violations[0].propertyPath" should contain "membership"
    And the JSON node "violations[0].message" should contain "Player cat't be added to inactive or not published membership"

  Scenario: Try to add player to a published and inactive membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/24/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "12_month",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/problem+json; charset=utf-8"
    Then the JSON node "violations" should exist
    And the JSON node "violations" should have 1 element
    And the JSON node "violations[0].propertyPath" should contain "membership"
    And the JSON node "violations[0].message" should contain "Player cat't be added to inactive or not published membership"

  Scenario: Try to add new membership card over existing future membership card
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/25/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "12_month",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 200
    And the JSON node "status" should contain "future"
    And the JSON node "state" should contain "new"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/26/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "annual_duration",
      "calendar_year": "2020-01-01",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 400
    And the JSON node "violations" should have 1 element
    And the JSON node "violations[0].propertyPath" should contain "player"
    And the JSON node "violations[0].message" should contain "There is future or upcoming membership already"

  Scenario: Add new 12_month membership card without fees (zero price)
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/32/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "12_month",
      "is_send_payment_link": false
    }
    """
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    And the JSON node "uuid" should exist
    And the JSON node "state" should contain "paid"
    And the JSON node "status" should contain "active"

  Scenario: Add new annual_duration membership card without fees (zero price)
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/33/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "annual_duration",
      "calendar_year": "2020-01-01",
      "is_send_payment_link": false
    }
    """
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    And the JSON node "uuid" should exist
    And the JSON node "state" should contain "paid"
    And the JSON node "status" should contain "upcoming"

  Scenario: Try to add new membership card over existing future membership card
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/32/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "12_month",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 200
    Then the JSON node "state" should contain "paid"
    Then the JSON node "status" should contain "active"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/26/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "annual_duration",
      "calendar_year": "2020-01-01",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 400
    And the JSON node "violations" should have 1 element
    And the JSON node "violations[0].propertyPath" should contain "duration_type"
    And the JSON node "violations[0].message" should contain "Invalid year. A membership is already exist for this year"

  Scenario: Try to add new membership card over existing upcoming membership card
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/33/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "annual_duration",
      "calendar_year": "2020-01-01",
      "is_send_payment_link": false
    }
    """
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "id" should exist
    And the JSON node "uuid" should exist
    And the JSON node "state" should contain "paid"
    And the JSON node "status" should contain "upcoming"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/memberships/25/card/new" with body:
    """
    {
      "player": 1,
      "duration_type": "12_month",
      "is_send_payment_link": false
    }
    """
    Then the response status code should be 400
    And the JSON node "violations" should have 1 element
    And the JSON node "violations[0].propertyPath" should contain "player"
    And the JSON node "violations[0].message" should contain "There is future or upcoming membership already"