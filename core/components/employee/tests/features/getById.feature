Feature: getById() method of employee component

  Scenario Outline:I try to get employee by its id
    Given I have component "employee"
    And method is "getById"
    And "id" parameter is <id>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get instance of "EmployeeContainer"
    And all fields from <fixtureId> in "employee" fixture should be equal to container "EmployeeContainer"

  Examples:
    | id          | fixtureId      |
    | "1"         | "first"        |
    | "2"         | "second"       |




  Scenario Outline:I fail to get employee with invalid id
    Given I have component "employee"
    And method is "getById"
    And "id" parameter is <id>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get null

  Examples:
    | id           |
    | "-13"        |
    | "0"          |
    | "175"        |
    | "1052153"    |