Feature: getAllEmployees() of Employee component

Scenario: I get all employees
    Given I have component "employee"
        And method is "getAllEmployees"
    When I call component method
    Then I should not get exception
        And I should get array
        And each of result items should be instance of "EmployeeContainer"