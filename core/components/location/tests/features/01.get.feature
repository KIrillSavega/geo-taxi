Feature: getAddressById(), getAllAddressesByIds(), getCountryNameByCode(), getCountryCodeByName()  methods of Location Component

  Scenario Outline: I get address by id
    Given I have component "location"
      And method is "getAddressById"
      And I set <location> "id" as first argument to method from "address" fixture
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get instance of "AddressContainer"
      And all fields from <location> in "address" fixture should be equal to container "AddressContainer"

  Examples:
    | location           |
    | "kharkov.addr"     |
    | "donetsk.addr"     |
    | "russian.addr"     |
    | "chinese.addr"     |
    | "london.addr"      |


  Scenario Outline: I get address by invalid id
    Given I have component "location"
      And method is "getAddressById"
      And I set <id> "id" as first argument to method from "user" fixture
     When I call component method
     Then I should not get exception
      And I should get null

  Examples:
    | id                 |
    | "qwerty"           |
    | "-1"               |
    | "9999"             |
    | "@=!%&7?"          |


  Scenario Outline: I get addresses by IDs
    Given I have component "location"
      And method is "getAllAddressesByIds"
      And I set <locations> "id" as first argument to method from "address" fixture
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get array of "AddressContainer" instances
      And all records from <locations> in "address" fixture should be equal to array of containers "AddressContainer"

  Examples:
    | locations                                                    |
    | "kharkov.addr;donetsk.addr;russian.addr;chinese.addr"        |
    | "washington.addr;moscow.addr;london.addr"                    |


  Scenario Outline: I get addresses by invalid IDs
    Given I have component "location"
      And method is "getAllAddressesByIds"
      And I set <locations> "id" as first argument to method from "address" fixture
     When I call component method
     Then I should not get exception
      And I should get empty array

  Examples:
    | locations                                   |
    | "notmarge.simpson;qwerty;-1;@=!%&7?;;;"     |
    | "asdfb@chmery;asdf"                         |



  Scenario Outline: I get country name by code
    Given I have component "location"
      And method is "getCountryNameByCode"
      And I set <code> "code" as first argument to method from "address" fixture
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And result should be equal to <name>

  Examples:
    | code     | name                          |
    | "ax"     | "Ahvenanmaan Laeaeni"         |
    | "BD"     | "Bangladesh"                  |
    | "De"     | "Federal Republic of Germany" |
    | "uA"     | "Ukraine"                     |
    | "wf"     | "Wallis et Futuna"            |


  Scenario Outline: I get country name by invalid code
    Given I have component "location"
      And method is "getCountryNameByCode"
      And I set <code> "code" as first argument to method from "address" fixture
     When I call component method
     Then I should not get exception
      And I should get null

  Examples:
    | code             |
    | "invalid"        |
    | "00000"          |
    | "@=!%&7?"        |
    | "33345678910"    |

  Scenario Outline: I get country code by name
    Given I have component "location"
      And method is "getCountryCodeByName"
      And I set <name> "name" as first argument to method from "address" fixture
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And result should be equal to <code>

  Examples:
    | code     | name                          |
    | "ax"     | "Ahvenanmaan Laeaeni"         |
    | "bd"     | "BaNGLadesh"                  |
    | "de"     | "Federal Republic of Germany" |
    | "ua"     | "ukraine"                     |
    | "wf"     | "Wallis et Futuna"            |


  Scenario Outline: I get country code by invalid name
    Given I have component "location"
      And method is "getCountryCodeByName"
      And I set <name> "name" as first argument to method from "address" fixture
     When I call component method
     Then I should not get exception
      And I should get null

  Examples:
    | name             |
    | "invalid"        |
    | "00000"          |
    | "@=!%&7?"        |
    | "33345678910"    |


