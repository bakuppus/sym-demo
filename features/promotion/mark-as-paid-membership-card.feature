@membership-card-mark-as-paid
Feature: Mark membership card as paid manually
  In order to mark membership as paid
  As a club admin
  I want to manually mark player's membership card as paid

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

  Scenario: Mark membership card as paid
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "/api/crm/membership-cards/1/mark-as-paid" with body:
    """
    {}
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the JSON node "id" should exist
    And the JSON node "uuid" should exist
    And the JSON node "expires_at" should exist
    And the JSON node "starts_at" should exist
    And the JSON node "state" should contain "paid"
    And the JSON node "status" should contain "upcoming"
    And the JSON node "starts_at" should not be null
    And the JSON node "expires_at" should not be null
