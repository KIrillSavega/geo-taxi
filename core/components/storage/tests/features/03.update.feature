Feature: updateFile() method of Storage Component

  Scenario Outline: I update file with valid values
    Given I have component "storage"
      And method is "updateFile"
      And I have "FileContainer" container for <file> that I have got by "getFileByUid" method with parameter "uid" from "file"
      And I set <value> to "FileContainer" container <field>
     When I set "FileContainer" container as "0" argument to component method
      And I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get instance of "FileContainer"
      And result container attribute <field> should be equal to <value>

  Examples:
    | file        | field            | value                                |
    | "file.01"   | "title"          | "Modified title for some file"       |
    | "file.08"   | "ext"            | "jpg"                                |
    | "file.09"   | "pathId"         | "988"                                |
    | "file.16"   | "created"        | "9865634"                            |
    | "file.15"   | "description"    | "jpg"                                |


  Scenario Outline: I update file with invalid values
    Given I have component "storage"
      And method is "updateFile"
      And I have "FileContainer" container for <file> that I have got by "getFileByUid" method with parameter "uid" from "file"
      And I set <value> to "FileContainer" container <field>
     When I set "FileContainer" container as "0" argument to component method
      And I call component method
     Then I should not get exception
      And I should get null
      And "getErrors" method should not return empty array
      And "getErrors" method should have <errorValidationFields> key in array of results

  Examples:
    | errorValidationFields | file         | field            | value               |
    | "ext"                 | "file.01"    | "ext"            | ""                  |
    | "ext"                 | "file.08"    | "ext"            | "invalidext"        |
    | "pathId"              | "file.09"    | "pathId"         | ""                  |
    | "pathId"              | "file.16"    | "pathId"         | "string"            |
    | "created"             | "file.15"    | "created"        | ""                  |
    | "created"             | "file.06"    | "created"        | "string"            |


  Scenario Outline: I update file non-editable fields
    Given I have component "storage"
      And method is "updateFile"
      And I have "FileContainer" container for <file> that I have got by "getFileByUid" method with parameter "uid" from "file"
      And I set <value> to "FileContainer" container <field>
     When I set "FileContainer" container as "0" argument to component method
      And I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get null

  Examples:
    | file       | field         | value        |
    | "file.01"  | "uid"         | "a45bc"      |
    | "file.09"  | "uid"         | "2"          |
