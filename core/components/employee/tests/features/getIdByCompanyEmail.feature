Feature: getIdByCompanyEmail() method of employee component

  Scenario Outline:I try to get employee id by his email
    Given I have component "employee"
    And method is "getIdByCompanyEmail"
    And "companyEmail" parameter is <companyEmail>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And result should be equal to "1"


  Examples:
    | companyEmail              |
    | "company@email.com"       |


  Scenario Outline:I fail to get employee id by his email
    Given I have component "employee"
    And method is "getIdByCompanyEmail"
    And "companyEmail" parameter is <companyEmail>
    When I call component method
    Then I should not get exception
    And "getErrors" method should return empty array
    And I should get null


  Examples:
    | companyEmail                   |
    | "not_existing@email.com"       |
