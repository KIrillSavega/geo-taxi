Feature: getFileByUid(), getAllFilesByUIDs(), getThumbImageUrlFromContainer(), getFilePathFromContainer(), getThumbFilePathFromContainer() methods of Storage Component

  Scenario Outline: I get file by uid
    Given I have component "storage"
      And method is "getFileByUid"
      And I set <file> "uid" as first argument to method from "file" fixture
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get instance of "FileContainer"
      And all fields from <file> in "file" fixture should be equal to container "FileContainer"

  Examples:
    | file          |
    | "file.01"     |
    | "file.16"     |
    | "file.15"     |
    | "file.04"     |
    | "file.09"     |


  Scenario Outline: I get file by invalid uid
    Given I have component "storage"
      And method is "getFileByUid"
      And I set <id> "uid" as first argument to method from "file" fixture
     When I call component method
     Then I should not get exception
      And I should get null

  Examples:
    | id                                   |
    | "2e3a32e3facac2658cb3efb111111111"   |
    | "-1"                                 |
    | "9999"                               |
    | "@=!%&7?"                            |
    | ""                                   |


  Scenario Outline: I get files by UIDs
    Given I have component "storage"
      And method is "getAllFilesByUIDs"
      And I set <files> "uid" as first argument to method from "file" fixture
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get array of "FileContainer" instances
      And all records from <files> in "file" fixture should be equal to array of containers "FileContainer"

  Examples:
    | files                                       |
    | "file.01;file.16;file.09;file.15"           |
    | "file.05;file.12;file.07;file.15;file.16"   |


  Scenario Outline: I get files by invalid UIDs
    Given I have component "storage"
      And method is "getAllFilesByUIDs"
      And I set <files> "uid" as first argument to method from "file" fixture
     When I call component method
     Then I should not get exception
      And I should get empty array

  Examples:
    | files                                  |
    | "invalid.id.charger;a#34;.bird;;;"     |
    | "asdfb@chmery;asdf;0"                  |


  Scenario Outline: I get thumb image url from file container
    Given I have component "storage"
      And method is "getThumbImageUrlFromContainer"
      And I have "FileContainer" container for <file> that I have got by "getFileByUid" method with parameter "uid" from "file"
      And I set "FileContainer" container as "0" argument to component method
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get type "string"
      And result should match to "url" based on "FileContainer" container with prefix "thumb_"

  Examples:
    | file         |
    | "file.01"    |
    | "file.09"    |
    | "file.15"    |
    | "file.16"    |


  Scenario Outline: I get thumb image url by invalid argument
    Given I have component "storage"
      And method is "getThumbImageUrlFromContainer"
      And I set <id> "id" as first argument to method from "file" fixture
     When I call component method
     Then I should not get exception
      And I should get null

  Examples:
    | id                 |
    | "qwerty"           |
    | "-1"               |
    | ""                 |
    | "@=!%&7?"          |


  Scenario Outline: I get file url from file container
    Given I have component "storage"
      And method is "getFileUrlFromContainer"
      And I have "FileContainer" container for <file> that I have got by "getFileByUid" method with parameter "uid" from "file"
      And I set "FileContainer" container as "0" argument to component method
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get type "string"
      And result should match to "url" based on "FileContainer" container with prefix ""

  Examples:
    | file         |
    | "file.01"    |
    | "file.09"    |
    | "file.15"    |
    | "file.16"    |


  Scenario Outline: I get file url by invalid argument
    Given I have component "storage"
      And method is "getFileUrlFromContainer"
      And I set <id> "id" as first argument to method from "file" fixture
     When I call component method
     Then I should not get exception
      And I should get null

  Examples:
    | id                 |
    | "qwerty"           |
    | "-1"               |
    | ""                 |
    | "@=!%&7?"          |


  Scenario Outline: I get file path from file container
    Given I have component "storage"
      And method is "getFilePathFromContainer"
      And I have "FileContainer" container for <file> that I have got by "getFileByUid" method with parameter "uid" from "file"
      And I set "FileContainer" container as "0" argument to component method
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get type "string"
      And result should match to "path" based on "FileContainer" container with prefix ""

  Examples:
    | file         |
    | "file.01"    |
    | "file.09"    |
    | "file.15"    |
    | "file.16"    |


  Scenario Outline: I get file path by invalid argument
    Given I have component "storage"
      And method is "getFilePathFromContainer"
      And I set <id> "id" as first argument to method from "file" fixture
     When I call component method
     Then I should not get exception
      And I should get null

  Examples:
    | id                 |
    | "qwerty"           |
    | "-1"               |
    | ""                 |
    | "@=!%&7?"          |


  Scenario Outline: I get thumb file path from file container
    Given I have component "storage"
      And method is "getThumbFilePathFromContainer"
      And I have "FileContainer" container for <file> that I have got by "getFileByUid" method with parameter "uid" from "file"
      And I set "FileContainer" container as "0" argument to component method
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get type "string"
      And result should match to "path" based on "FileContainer" container with prefix "thumb_"

  Examples:
    | file         |
    | "file.01"    |
    | "file.09"    |
    | "file.15"    |
    | "file.16"    |


  Scenario Outline: I get thumb file path by invalid argument
    Given I have component "storage"
      And method is "getThumbFilePathFromContainer"
      And I set <id> "id" as first argument to method from "file" fixture
     When I call component method
     Then I should not get exception
      And I should get null

  Examples:
    | id                 |
    | "qwerty"           |
    | "-1"               |
    | ""                 |
    | "@=!%&7?"          |
