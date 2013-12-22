Feature: getAllEmployeePermissionGroups() method of employee component

  Scenario:I try to get all employee permission groups
    Given I have component "employee"
    And method is "getAllEmployeePermissionGroups"
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And "getAllEmployeePermissionGroups" method should not return empty array
