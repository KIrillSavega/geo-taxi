Feature: updateEmployeePermissionGroup() method of employee component

  Scenario Outline: I update employee with valid values
    Given I have component "employee"
    And method is "updateEmployeePermissionGroup"
    And I have "EmployeePermissionGroupContainer" container for <fixtureId> that I have got by "getEmployeePermissionGroupById" method with parameter "id" from "employee_permission_group"
    And I set <value> to "EmployeePermissionGroupContainer" container <field>
    When I set "EmployeePermissionGroupContainer" container as "0" argument to component method
    And I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get instance of "EmployeePermissionGroupContainer"

  Examples:
    | fixtureId       | field              | value                   |
    | "first"         | "title"            | "Title is updated"      |
    | "second"        | "updateCompany"    | "0"                     |


  Scenario Outline: I update employee with invalid values
    Given I have component "employee"
    And method is "updateEmployeePermissionGroup"
    And I have "EmployeePermissionGroupContainer" container for <fixtureId> that I have got by "getEmployeePermissionGroupById" method with parameter "id" from "employee_permission_group"
    And I set <value> to "EmployeePermissionGroupContainer" container <field>
    When I set "EmployeePermissionGroupContainer" container as "0" argument to component method
    And I call component method
    Then "getErrors" method should return empty array
    And I should get null

  Examples:
    | fixtureId       | field              | value            |
    | "first"         | "id"               | "#^$%#$^#$"      |


