Feature: get methods of Storage Component

  Scenario Outline: I get images by gallery id
    Given I have component "storage"
      And method is "getGalleryImagesBySalesOutletId"
      And I set <sales_outlet> "id" as first argument to method from "sales_outlet" fixture
     When I call method from subcomponent "gallery"
     Then I should not get exception
      And "getErrors" method should return empty array
      And I should get array of "FileContainer" instances
      And all result array records "uid" have to be equal to selection "image_uid" from "image2gallery" fixture by field "gallery_id" equal to <gallery> "gallery_id" from fixture "image2gallery"

  Examples:
    | gallery            | sales_outlet |
    | "image.gallery.1"  | "first"      |


  Scenario Outline: I get images by invalid gallery id
    Given I have component "storage"
      And method is "getGalleryImagesBySalesOutletId"
      And I set <id> "id" as first argument to method from "image2gallery" fixture
     When I call method from subcomponent "gallery"
     Then I should not get exception
      And I should get empty array

  Examples:
    | id                      |
    | "2e3a32e3facac2658cb"   |
    | "-1"                    |
    | "9999"                  |
    | "@=!%&7?"               |
    | ""                      |
