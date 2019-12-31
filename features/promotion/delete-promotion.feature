@delete-promotion @promotion
Feature: Delete promotion
  In order to manage promotions
  As a club admin
  I want be able delete promotions

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/club.yml             |
      | fixtures/membership.yml       |
      | fixtures/promotion.yml        |
      | fixtures/promotion_rule.yml   |
      | fixtures/promotion_action.yml |
    Given I populate the indexes

  Scenario: Delete Promotion with related rules and actions
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "DELETE" request to "/api/crm/promotions/1"
    Then the response status code should be 204
    And I add "Accept" header equal to "application/json"
    And I send a "GET" request to "/api/promotions/1"
    Then the response status code should be 404
    And entity "App\Domain\Promotion\PromotionRule" doesnt have item with condition:
      | promotion |
      | 1         |
    And entity "App\Domain\Promotion\PromotionAction" doesnt have item with condition:
      | promotion |
      | 1         |
