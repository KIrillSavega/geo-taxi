Feature: updateEmployee() method of employee component

  Scenario Outline: I update employee with valid values
    Given I have component "employee"
    And method is "updateEmployee"
    And I have "EmployeeContainer" container for <fixtureId> that I have got by "getById" method with parameter "id" from "employee"
    And I set <value> to "EmployeeContainer" container <field>
    When I set "EmployeeContainer" container as "0" argument to component method
    And I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get instance of "EmployeeContainer"

  Examples:
    | fixtureId       | field         | value                   |
    | "first"         | "lastName"    | "Last name is updated"  |
    | "second"        | "password"    | "Password is updated"   |


  Scenario Outline: I update employee with invalid values
    Given I have component "employee"
    And method is "updateEmployee"
    And I have "EmployeeContainer" container for <fixtureId> that I have got by "getById" method with parameter "id" from "employee"
    And I set <value> to "EmployeeContainer" container <field>
    When I set "EmployeeContainer" container as "0" argument to component method
    And I call component method
    Then "getErrors" method should not return empty array

  Examples:
    | fixtureId       | field           | value                       |
    | "first"         | "isActive"      | "false"                     |
    | "second"        | "posPinCode"    | "Pos pin code is updated"   |
    | "first"         | "companyEmail"  | "a"                         |