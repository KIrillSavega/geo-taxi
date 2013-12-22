Feature: createAddress() method of Location Component

  Scenario Outline: I create address with valid values
    Given I have component "location"
      And method is "createAddress"
      And I have object of container "AddressContainer"
      And I set "addressLine1" key to "AddressContainer" object with value <addressLine1>
      And I set "addressLine2" key to "AddressContainer" object with value <addressLine2>
      And I set "city" key to "AddressContainer" object with value <city>
      And I set "region" key to "AddressContainer" object with value <region>
      And I set "postalCode" key to "AddressContainer" object with value <postalCode>
      And I set "country" key to "AddressContainer" object with value <country>
     When I set "AddressContainer" container as "0" argument to component method
      And I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get instance of "AddressContainer"
      And new record from container should be created in "address" table
      And "addressLine1" field from "address" table is equal to <addressLine1> and equal to "addressLine1" field of returned container
      And "addressLine2" field from "address" table is equal to <addressLine2> and equal to "addressLine2" field of returned container
      And "city" field from "address" table is equal to <city> and equal to "city" field of returned container
      And "region" field from "address" table is equal to <region> and equal to "region" field of returned container
      And "postalCode" field from "address" table is equal to <postalCode> and equal to "postalCode" field of returned container
      And "country" field from "address" table is equal to <country> and equal to "country" field of returned container

  Examples:
    | addressLine1               | addressLine2          | city         | region             | postalCode | country |
    | "Molodezhnaya str. b 22"   | "apt. 33"             | "Lugansk"    | "Luganskaya obl."  | "58000"    | "ua"    |
    | "Moskalevskaya str., b m1" | "Bendery bld, cab 52" | "Lviv"       | "Lvivska obl."     | "BL-65"    | "li"    |
    | "ул. Невидимая, р-н Z"     | "бизнес-центр Стрела" | "Запорожье"  | "Запорожская обл." | "987600"   | "bj"    |
    | "Some symbols 約翰 約翰"    | "China 約翰 約翰"      | "約翰 約翰"   | "約翰 約翰"         | "089000"   | "wf"    |


  Scenario Outline: I create address with invalid values
    Given I have component "location"
      And method is "createAddress"
      And I have object of container "AddressContainer"
      And I set "addressLine1" key to "AddressContainer" object with value <addressLine1>
      And I set "addressLine2" key to "AddressContainer" object with value <addressLine2>
      And I set "city" key to "AddressContainer" object with value <city>
      And I set "region" key to "AddressContainer" object with value <region>
      And I set "postalCode" key to "AddressContainer" object with value <postalCode>
      And I set "country" key to "AddressContainer" object with value <country>
     When I set "AddressContainer" container as "0" argument to component method
      And I call component method
     Then I should not get exception
      And I should get type "NULL"
      And "getErrors" method should not return empty array
      And "getErrors" method should have <errorValidationFields> key in array of results

  Examples:
    | errorValidationFields | addressLine1               | addressLine2          | city         | region             | postalCode | country |
    | "postalCode"          | "Molodezhnaya str. b 22"   | "apt. 33"             | "Lugansk"    | "Luganskaya obl."  | "# : asf"  | "ua"    |
    | "country"             | "Moskalevskaya str., b m1" | "Bendery bld, cab 52" | "Lviv"       | "Lvivska obl."     | "98623"    | "X"     |
    | "country"             | "ул. Не должна появиться"  | "бизнес-центр Стрела" | "Запорожье"  | "Запорожская обл." | "987600"   | "long"  |
    | "postalCode, country" | "Should not be 約翰 約翰"   | "China 約翰 約翰"      | "約翰 約翰"   | "約翰 約翰"         | "$&?"      | "some"  |





