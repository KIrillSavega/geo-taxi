Feature: getEmployeeByPinCode() method of employee component

  Scenario Outline:I try to get employee with his pin code
    Given I have component "employee"
    And method is "getEmployeeByPinCode"
    And "posPinCode" parameter is <posPinCode>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get instance of "EmployeeContainer"
    And result container attribute "posPinCode" should be equal to <posPinCode>


  Examples:
    | posPinCode    |
    | "1111"        |


  Scenario Outline:I fail to get employee with his pin code
    Given I have component "employee"
    And method is "getEmployeeByPinCode"
    And "posPinCode" parameter is <posPinCode>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get null


  Examples:
    | posPinCode    |
    | "6666"        |
