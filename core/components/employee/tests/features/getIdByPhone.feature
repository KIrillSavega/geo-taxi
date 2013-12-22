Feature: getIdByPhone() method of employee component

  Scenario Outline:I try to get employee id by his phone
    Given I have component "employee"
    And method is "getIdByPhone"
    And "mobilePhone" parameter is <mobilePhone>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And result should be equal to "1"


  Examples:
    | mobilePhone               |
    | "+380000000000"           |


  Scenario Outline:I fail to get employee id by his phone
    Given I have component "employee"
    And method is "getIdByPhone"
    And "mobilePhone" parameter is <mobilePhone>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get null


  Examples:
    | mobilePhone               |
    | "+380001234567"           |
