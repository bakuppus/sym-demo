@filter @range-filter
Feature: Show list
  According to range

  Background:
    Given I empty the database
    Given I reset the indexes
    Given the following fixtures files are loaded:
      | fixtures/tee-time.yml |
      | fixtures/course.yml   |
    Given I populate the indexes
