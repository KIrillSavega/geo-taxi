Feature: checkHasPermission() method of employee component

  Scenario Outline:I check permission with employee and permission
    Given I have component "employee"
    And method is "checkHasPermission"
    And I have "EmployeeContainer" container for <fixtureId> that I have got by "getById" method with parameter "id" from "employee"
    And "employee" parameter is "EmployeeContainer"
    And "permission" parameter is <permission>
    When I set "EmployeeContainer" container as "employee" argument to component method
    And I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get type "boolean"

  Examples:
    | fixtureId        | permission                          |
    | "first"          | "viewParentCompany"                 |
    | "second"         | "updateParentCompanySettings"       |



  Scenario Outline:I fail to check permission with invalid employee and permission
    Given I have component "employee"
    And method is "checkHasPermission"
    And I have "EmployeeContainer" container for <fixtureId> that I have got by "getById" method with parameter "id" from "employee"
    And "employee" parameter is "EmployeeContainer"
    And "permission" parameter is <permission>
    When I set "EmployeeContainer" container as "employee" argument to component method
    And I call component method
    Then I should get exception
    And "getErrors" method should return empty array
    And I should get null

  Examples:
    | fixtureId        | permission                          |
    | "first"          | "lookParentCompany"                 |
    | "second"         | "@#^@%$%^!#%!$$!"                   |