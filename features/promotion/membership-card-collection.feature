@membership-card-collection @membership-card-list
Feature: Show membership card list
  In order to manage membership card
  As a club admin
  I want to have a membership card list

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml           |
      | fixtures/course.yml         |
      | fixtures/player.yml         |
      | fixtures/membership.yml     |
      | fixtures/fee-unit.yml       |
      | fixtures/membership-fee.yml |
      | fixtures/membership-card.yml |
      | fixtures/gateway_config.yml |
      | fixtures/payment_method.yml |
      | fixtures/order.yml |
      | fixtures/order-membership.yml |
      | fixtures/order-item-membership-card.yml |
      | fixtures/payment.yml |
    Given I populate the indexes

  Scenario: List all membership cards
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/membership-cards"
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    Then the JSON node "root" should have 50 elements
    Then the JSON node "root[0].id" should exist
    Then the JSON node "root[0].uuid" should exist
    Then the JSON node "root[0].expires_at" should exist
    Then the JSON node "root[0].starts_at" should exist
    Then the JSON node "root[0].duration_type" should exist
    Then the JSON node "root[0].player" should exist
    Then the JSON node "root[0].membership" should exist
    Then the JSON node "root[0].club" should exist
