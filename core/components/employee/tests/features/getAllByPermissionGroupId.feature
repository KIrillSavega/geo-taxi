Feature: getAllByPermissionGroupId() method of employee component

  Scenario Outline: I get all employees by permission group id
    Given I have component "employee"
    And method is "getAllByPermissionGroupId"
    And I set <fixtureId> "id" as first argument to method from "employee" fixture
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get array of "EmployeeContainer" instances
    And I should not get empty array

  Examples:
    | fixtureId |
    | "first"   |

  Scenario Outline: I fail to get employees by permission group id
    Given I have component "employee"
    And method is "getAllByPermissionGroupId"
    And I set <fixtureId> "id" as first argument to method from "employee" fixture
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get array of "EmployeeContainer" instances
    And I should get empty array

  Examples:
    | fixtureId |
    | "third"   |

