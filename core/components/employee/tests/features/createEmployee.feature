Feature: createEmployee() method of employee component

  Scenario Outline: I create employee with container
    Given I have component "employee"
    And method is "createEmployee"
    And I have object of container "EmployeeContainer"
    And I set "permissionGroupId" key to "EmployeeContainer" object with value <permissionGroupId>
    And I set "firstName" key to "EmployeeContainer" object with value <firstName>
    And I set "lastName" key to "EmployeeContainer" object with value <lastName>
    And I set "companyEmail" key to "EmployeeContainer" object with value <companyEmail>
    And I set "privateEmail" key to "EmployeeContainer" object with value <privateEmail>
    And I set "mobilePhone" key to "EmployeeContainer" object with value <mobilePhone>
    And I set "password" key to "EmployeeContainer" object with value <password>
    And I set "posPinCode" key to "EmployeeContainer" object with value <posPinCode>
    And I set "isActive" key to "EmployeeContainer" object with value <isActive>
    And I set "companyId" key to "EmployeeContainer" object with value <companyId>
    When I set "EmployeeContainer" container as "0" argument to component method
    And I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get instance of "EmployeeContainer"
    And result container should have not null attribute "id"
    And password <password> should be hashed by "sha256" algorithm with "ahVahw7woo" salt


  Examples:
    | permissionGroupId | firstName         | lastName       | companyEmail          | privateEmail                  | mobilePhone      | password     | posPinCode | isActive | companyId |
    | "1"               | "Joe"             | "Black"        | "yes@mail.com"        | "private@email.com"           | "32905829305"    | "111111"     | ""         | "1"      | "1"       |
    | "1"               | "Joe"             | "Black"        | "yez@mail.com"        | "new_email@mail.com"          | "000000000011"   | "111111"     | ""         | "1"      | "2"       |



  Scenario Outline: I fail to create employee with container because of invalid values
    Given I have component "employee"
    And method is "createEmployee"
    And I have object of container "EmployeeContainer"
    And I set "permissionGroupId" key to "EmployeeContainer" object with value <permissionGroupId>
    And I set "firstName" key to "EmployeeContainer" object with value <firstName>
    And I set "lastName" key to "EmployeeContainer" object with value <lastName>
    And I set "companyEmail" key to "EmployeeContainer" object with value <companyEmail>
    And I set "privateEmail" key to "EmployeeContainer" object with value <privateEmail>
    And I set "mobilePhone" key to "EmployeeContainer" object with value <mobilePhone>
    And I set "password" key to "EmployeeContainer" object with value <password>
    And I set "posPinCode" key to "EmployeeContainer" object with value <posPinCode>
    And I set "isActive" key to "EmployeeContainer" object with value <isActive>
    And I set "companyId" key to "EmployeeContainer" object with value <companyId>
    When I set "EmployeeContainer" container as "0" argument to component method
    And I call component method
    Then I should not get exception
    And "getErrors" method should not return empty array
    And I should get null


  Examples:
    | permissionGroupId | firstName         | lastName       | companyEmail          | privateEmail       | mobilePhone      | password     | posPinCode | isActive | companyId |
    | "1"               | "Joe"             | "Black"        | "yes@mail.com"        | "no@mail.com"      | "32905829305"    | "1111"       | ""         | "no"     | "1"       |
    | "2"               | "No"              | "Name"         | "a"                   | "b"                | "no"             | ""           | "12312"    | "1"      | "2"       |



