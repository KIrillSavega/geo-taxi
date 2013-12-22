Feature: createEmployeePermissionGroup() method of employee component

  Scenario Outline: I create employee permission group with container
    Given I have component "employee"
    And method is "createEmployeePermissionGroup"
    And I have object of container "EmployeePermissionGroupContainer"
    And I set "title" key to "EmployeePermissionGroupContainer" object with value <title>
    And I set "viewParentCompany" key to "EmployeePermissionGroupContainer" object with value <viewParentCompany>
    And I set "editParentCompanySettings" key to "EmployeePermissionGroupContainer" object with value <editParentCompanySettings>
    And I set "createEmployee" key to "EmployeePermissionGroupContainer" object with value <createEmployee>
    And I set "updateEmployee" key to "EmployeePermissionGroupContainer" object with value <updateEmployee>
    And I set "createCompany" key to "EmployeePermissionGroupContainer" object with value <createCompany>
    And I set "updateCompany" key to "EmployeePermissionGroupContainer" object with value <updateCompany>
    And I set "createSalesOutlet" key to "EmployeePermissionGroupContainer" object with value <createSalesOutlet>
    And I set "updateSalesOutlet" key to "EmployeePermissionGroupContainer" object with value <updateSalesOutlet>
    When I set "EmployeePermissionGroupContainer" container as "0" argument to component method
    And I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get instance of "EmployeePermissionGroupContainer"
    And result container should have not null attribute "id"


  Examples:
    | title              | viewParentCompany | editParentCompanySettings | createEmployee | updateEmployee | createCompany  | updateCompany | createSalesOutlet | updateSalesOutlet |
    | "GOD"              | "1"               | "1"                       | "1"            | "1"            | "1"            | "1"           | "1"               | "1"               |
    | "lamer"            | "0"               | "0"                       | "0"            | "0"            | "0"            | "0"           | "0"               | "0"               |
    | "nobody"           | ""                | ""                        | ""             | ""             | ""             | ""            | ""                | ""                |



  Scenario Outline: I fail to create employee permission group with container because of invalid values
    Given I have component "employee"
    And method is "createEmployee"
    And I have object of container "EmployeePermissionGroupContainer"
    And I set "title" key to "EmployeePermissionGroupContainer" object with value <title>
    And I set "viewParentCompany" key to "EmployeePermissionGroupContainer" object with value <viewParentCompany>
    And I set "editParentCompanySettings" key to "EmployeePermissionGroupContainer" object with value <editParentCompanySettings>
    And I set "createEmployee" key to "EmployeePermissionGroupContainer" object with value <createEmployee>
    And I set "updateEmployee" key to "EmployeePermissionGroupContainer" object with value <updateEmployee>
    And I set "createCompany" key to "EmployeePermissionGroupContainer" object with value <createCompany>
    And I set "updateCompany" key to "EmployeePermissionGroupContainer" object with value <updateCompany>
    And I set "createSalesOutlet" key to "EmployeePermissionGroupContainer" object with value <createSalesOutlet>
    And I set "updateSalesOutlet" key to "EmployeePermissionGroupContainer" object with value <updateSalesOutlet>
    When I set "EmployeePermissionGroupContainer" container as "0" argument to component method
    And I call component method
    And "getErrors" method should return empty array
    And I should get null


  Examples:
    | title              | viewParentCompany | editParentCompanySettings | createEmployee | updateEmployee | createCompany  | updateCompany | createSalesOutlet | updateSalesOutlet |
    | "GOD"              | "yes"             | "1"                       | "1"            | "1"            | "1"            | "1"           | "1"               | "1"               |
    | "lamer"            | "0"               | "no"                      | "0"            | "0"            | "0"            | "0"           | "0"               | "0"               |



