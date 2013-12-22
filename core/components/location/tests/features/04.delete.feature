Feature: deleteAddressById() method of Location Component

  Scenario Outline: I delete address by id
    Given I have component "location"
      And method is "deleteAddressById"
      And I set <address> "id" as first argument to method from "address" fixture
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And "getAddressById" method of current component with <address> "id" parameter from fixture "address" should return null

  Examples:
    | address           |
    | "kyiv.addr"       |
    | "london.addr"     |
    | "chinese.addr"    |


  Scenario Outline: I delete address by invalid id
    Given I have component "location"
      And method is "deleteAddressById"
      And I set <address> "id" as first argument to method from "address" fixture
     When I call component method
     Then I should not get exception
      And result should be equal to "0"

  Examples:
    | address       |
    | "invalid"     |
    | "!@#$%^&*("   |
    | "sadf"        |
