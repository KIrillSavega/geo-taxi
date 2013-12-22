Feature: createFile() method of Storage Component

  Scenario Outline: I create file with valid values
    Given I have component "storage"
      And method is "createFile"
      And I have object of container "FileContainer"
      And I set "ext" key to "FileContainer" object with value <ext>
      And I set "uid" key to "FileContainer" object with value <uid>
      And I set "pathId" key to "FileContainer" object with value <path_id>
      And I set "title" key to "FileContainer" object with value <title>
      And I set "description" key to "FileContainer" object with value <description>
     When I set "FileContainer" container as "0" argument to component method
      And I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get instance of "FileContainer"
      And new record from container should be created in "file" table with PK "uid"
      And "ext" field from "file" table with PK "uid" is equal to <ext> and equal to "ext" field of returned container
      And "uid" field from "file" table with PK "uid" is equal to <uid> and equal to "uid" field of returned container
      And "pathId" field from "file" table with PK "uid" is equal to <path_id> and equal to "pathId" field of returned container
      And "title" field from "file" table with PK "uid" is equal to <title> and equal to "title" field of returned container
      And "description" field from "file" table with PK "uid" is equal to <description> and equal to "description" field of returned container
      And result container should have not null attribute "created"
      And "created" field from "file" table with PK "uid" is equal to "created" field of returned container

  Examples:
    | title                        | uid                                | ext    | path_id  | description                          |
    | "Soda beverages"             | "2e3a37e3faabc2658cb3efb1ce8ba6d5" | "jpg"  | "10"     | "New description"                    |
    | "Cake"                       | "32d967a0dfadc36634ba50613bbc1f70" | "jpeg" | "11"     | "Very tasty"                         |
    | "Mixed кириллица + 约翰 约翰" | "3f2da77a67ecc88bab0a7dcdc4273d08" | "png"  | "12"     | "Mixed с кириллицей и english"       |
    | "Русский some russian staff" | "42c9ad07cd4ac6bcb578de04511b1e24" | "jpg"  | "1"      | "Russian описание c кириллицей"      |
    | "约翰 约翰 some chinese"      | "436fae882aacacebe42f69ec0ee29ff2" | "jpeg" | "20"     | "约翰 约翰 some chinese"              |


  Scenario Outline: I create file with invalid values
    Given I have component "storage"
      And method is "createFile"
      And I have object of container "FileContainer"
      And I set "ext" key to "FileContainer" object with value <ext>
      And I set "uid" key to "FileContainer" object with value <uid>
      And I set "pathId" key to "FileContainer" object with value <path_id>
      And I set "title" key to "FileContainer" object with value <title>
      And I set "description" key to "FileContainer" object with value <description>
     When I set "FileContainer" container as "0" argument to component method
      And I call component method
     Then I should not get exception
      And I should get type "NULL"
      And "getErrors" method should not return empty array
      And "getErrors" method should have <errorValidationFields> key in array of results

  Examples:
    | errorValidationFields | title         | uid       | ext          | path_id  | description                          |
    | "uid"                 | ""            | ""        | "jpg"        | "10"     | "New description"                    |
    | "pathId"              | "Some title"  | "asd"     | "png"        | "asd"    | "Very tasty"                         |
    | "pathId"              | "Some title"  | "asd"     | "png"        | ""       | "Very tasty"                         |
    | "ext"                 | "Some title"  | "asd"     | "asdfasdf"   | "12"     | "Mixed с кириллицей и english"       |
    | "ext"                 | "Some title"  | "asd"     | ""           | "12"     | "Mixed с кириллицей и english"       |
    | "uid"                 | ""            | "asdfasdfasdfasdfasdfasdfasdfasdfafasdfasfdasdfafdasf"      | "jpg"        | "10"     | "New description"   |
