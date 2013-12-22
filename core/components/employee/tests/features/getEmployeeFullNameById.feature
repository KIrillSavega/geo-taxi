Feature: getEmployeeFullNameById() of Employee component

Scenario Outline: I get employee fullname
    Given I have component "employee"
        And method is "getEmployeeFullNameById"
        And "id" parameter is <id>
    When I call component method
    Then I should not get exception
        And I should get string
        And result should consist of employee with id <id> first and last names separated by space

Examples:
| id  |
| "1" |