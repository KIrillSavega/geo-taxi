Feature: getIdByPinCode() method of employee component

  Scenario Outline:I try to get employee id by his pin code
    Given I have component "employee"
    And method is "getIdByPinCode"
    And "posPinCode" parameter is <posPinCode>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And result should be equal to "1"


  Examples:
    | posPinCode       |
    | "1111"           |


  Scenario Outline:I fail to get employee id by his pin code
    Given I have component "employee"
    And method is "getIdByPinCode"
    And "posPinCode" parameter is <posPinCode>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get null


  Examples:
    | posPinCode       |
    | "6666"           |
