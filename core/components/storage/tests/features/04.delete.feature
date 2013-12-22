Feature: delete methods of Storage Component

  Scenario Outline: I delete file by uid
    Given I have component "storage"
      And method is "deleteFile"
      And I set <file> "uid" as first argument to method from "file" fixture
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And "getFileByUid" method of current component with <file> "uid" parameter from fixture "file" should return null

  Examples:
    | file           |
    | "file.01"      |
    | "file.07"      |


  Scenario Outline: I delete file by invalid uid
    Given I have component "storage"
      And method is "deleteFile"
      And I set <uid> "uid" as first argument to method from "file" fixture
     When I call component method
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get null

  Examples:
    | uid            |
    | "invalid"      |
    | "!@#$%^&"      |
