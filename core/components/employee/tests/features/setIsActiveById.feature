Feature: setIsActiveById() method of employee component

  Scenario Outline: I set active status to employee with valid id
    Given I have component "employee"
    And method is "setIsActiveById"
    And "id" parameter is <id>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get instance of "EmployeeContainer"
    And result container attribute "isActive" should be equal to "true"
    And result container attribute "id" should be equal to <id>

  Examples:
    | id         |
    | "1"        |
    | "2"        |


  Scenario Outline: I fail to set active status to employee with invalid id
    Given I have component "employee"
    And method is "setIsActiveById"
    And "id" parameter is <id>
    When I call component method
    Then "getErrors" method should return empty array
    And I should get null

  Examples:
    | id       |
    | "185"    |
    | "-13"    |
    | "1241"   |
    | "#^!@!*" |