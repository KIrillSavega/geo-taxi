Feature: checkHasPermissionById() method of employee component

  Scenario Outline:I check permission with employee and permission
    Given I have component "employee"
    And method is "checkHasPermissionById"
    And "employeeId" parameter is <employeeId>
    And "permission" parameter is <permission>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get type "boolean"

  Examples:
    | employeeId         | permission                |
    | "1"                | "viewParentCompany"       |
    | "1"                | "updateBrand"             |


  Scenario Outline:I fail to check permission with employee and permission
    Given I have component "employee"
    And method is "checkHasPermissionById"
    And "employeeId" parameter is <employeeId>
    And "permission" parameter is <permission>
    When I call component method
    Then I should get exception
    And "getErrors" method should return empty array
    And I should get null

  Examples:
    | employeeId         | permission                |
    | "4"                | "viewParentCompany"       |
    | "1"                | "updatedBrand"            |
