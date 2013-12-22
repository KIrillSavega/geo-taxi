Feature: updateAddress() method of Location Component

  Scenario Outline: I update address with valid values
    Given I have component "location"
      And method is "updateAddress"
      And I have "AddressContainer" container for <address> that I have got by "getAddressById" method with parameter "id" from "address"
      And I set <value> to "AddressContainer" container <field>
     When I set "AddressContainer" container as "0" argument to component method
      And I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get instance of "AddressContainer"


  Examples:
     | address         | field          | value                 |
     | "kharkov.addr"  |"addressLine1"  | "New Address Line 1"  |
     | "kharkov.addr"  |"addressLine2"  | "New Address Line 2"  |
     | "kharkov.addr"  |"city"          | "New Kharkovr"        |
     | "kharkov.addr"  |"region"        | "Slobozhanskiy"       |
     | "kharkov.addr"  |"postalCode"    | "S-9999999-Kh"        |
     | "kharkov.addr"  |"country"       | "ru"                  |