@promotion @membership @publish-membership
Feature: Publish membership
  In order to release membership
  As a club admin
  I want to publish membership

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

  Scenario: Successfully publish membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/memberships/1/publish" with body:
    """
    {}
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json; charset=utf-8"
    And the JSON node "id" should exist
    And the JSON node "state" should contain "published"

  Scenario: Try to publish already published membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/memberships/24/publish" with body:
    """
    {}
    """
    Then the response status code should be 400
    Then the JSON node "violations" should exist
    Then the JSON node "violations[0].propertyPath" should contain "state"
    Then the JSON node "violations[0].message" should contain "Membership can't be published"

  Scenario: Try to publish already outdated membership
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "PUT" request to "api/crm/memberships/14/publish" with body:
    """
    {}
    """
    Then the response status code should be 400
    Then the JSON node "violations[0].propertyPath" should contain "state"
    Then the JSON node "violations[0].message" should contain "Membership can't be published"
