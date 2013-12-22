Feature: getAllByIds() method of employee component

  Scenario Outline:I try to get employees by their ids
    Given I have component "employee"
    And method is "getAllByIds"
    And I set <IDs> "id" as first argument to method from "employee" fixture
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get array of "EmployeeContainer" instances
    And all records from <IDs> in "employee" fixture should be equal to array of containers "EmployeeContainer"

  Examples:
    | IDs                |
    | "first;second"     |



  Scenario Outline:I fail to get employees with invalid ids
    Given I have component "employee"
    And method is "getAllByIds"
    And I set <IDs> "id" as first argument to method from "employee" fixture
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get empty array

  Examples:
    | IDs               |
    | "first key; #1"   |
    | "-13; 0; 42523"   |
    | "175; -235"       |
    | "1052153; 934534" |
