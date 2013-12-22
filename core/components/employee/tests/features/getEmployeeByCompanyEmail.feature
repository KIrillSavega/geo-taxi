Feature: getEmployeeByCompanyEmail() method of employee component

  Scenario Outline:I try to get employee with his email
    Given I have component "employee"
    And method is "getEmployeeByCompanyEmail"
    And "companyEmail" parameter is <companyEmail>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get instance of "EmployeeContainer"
    And result container attribute "companyEmail" should be equal to <companyEmail>


  Examples:
    | companyEmail                 |
    | "company@email.com"          |


  Scenario Outline:I fail to get employee with his invalid email
    Given I have component "employee"
    And method is "getEmployeeByCompanyEmail"
    And "companyEmail" parameter is <companyEmail>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get null


  Examples:
    | companyEmail                 |
    | "not_existing@email.com"     |
