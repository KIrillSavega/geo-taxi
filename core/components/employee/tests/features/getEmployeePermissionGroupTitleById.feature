Feature: getEmployeePermissionGroupTitleById() method of employee component

  Scenario Outline:I try to get employee permission group title with its id
    Given I have component "employee"
    And method is "getEmployeePermissionGroupTitleById"
    And "id" parameter is <id>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And result should be equal to <title>


  Examples:
    | id     | title                        |
    | "1"    | "Super Admin"                |


  Scenario Outline:I fail to get employee permission group title with its id
    Given I have component "employee"
    And method is "getEmployeePermissionGroupTitleById"
    And "id" parameter is <id>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get null


  Examples:
    | id       |
    | "@#$"    |
    | "-13"    |
    | "666"    |
