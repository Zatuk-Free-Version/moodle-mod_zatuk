@mod @mod_zatuk @core_completion
Feature: View activity completion information in the zatuk resource
  In order to have visibility of zatuk completion requirements
  As a student
  I need to be able to view my zatuk completion progress

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | student1 | Vinnie    | Student1 | student1@example.com |
      | teacher1 | Darrell   | Teacher1 | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category | enablecompletion | showcompletionconditions |
      | Course 1 | C1        | 0        | 1                | 1                        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | student1 | C1     | student        |
      | teacher1 | C1     | editingteacher |
    And the following config values are set as admin:
      | displayoptions | 0,1,2,3,4,5,6 | zatuk |

  Scenario: View automatic completion items in automatic display mode as teacher
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 2                   |
      | completionview | 1                   |
      | display        | 0                   |
    When I am on the "Zatuk history" "zatuk activity" page logged in as teacher1
    Then "Zatuk history" should have the "View" completion condition

  Scenario: View automatic completion items in automatic display mode as student
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 2                   |
      | completionview | 1                   |
      | display        | 0                   |
    When I am on the "Zatuk history" "zatuk activity" page logged in as student1
    Then the "View" completion condition of "Zatuk history" is displayed as "done"

  Scenario: View automatic completion items in embed display mode as teacher
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 2                   |
      | completionview | 1                   |
      | display        | 1                   |
    When I am on the "Zatuk history" "zatuk activity" page logged in as teacher1
    Then "Zatuk history" should have the "View" completion condition

  Scenario: View automatic completion items in embed display mode as student
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 2                   |
      | completionview | 1                   |
      | display        | 1                   |
    When I am on the "Zatuk history" "zatuk activity" page logged in as student1
    Then the "View" completion condition of "Zatuk history" is displayed as "done"

  Scenario: View automatic completion items in open display mode as teacher
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 2                   |
      | completionview | 1                   |
      | display        | 5                   |
    When I am on the "Zatuk history" "zatuk activity" page logged in as teacher1
    And I am on the "Course 1" course page
    Then "Zatuk history" should have the "View" completion condition

  Scenario: View automatic completion items in open display mode as student
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 2                   |
      | completionview | 1                   |
      | display        | 5                   |
    When I am on the "Zatuk history" "zatuk activity" page logged in as student1
    And I am on the "Course 1" course page
    Then the "View" completion condition of "Zatuk history" is displayed as "done"

  Scenario: View automatic completion items in pop-up display mode as teacher
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 2                   |
      | completionview | 1                   |
      | display        | 6                   |
      | popupwidth     | 620                 |
      | popupheight    | 450                 |
    When I am on the "Zatuk history" "zatuk activity" page logged in as student1
    Then "Zatuk history" should have the "View" completion condition

  Scenario: View automatic completion items in pop-up display mode as student
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 2                   |
      | completionview | 1                   |
      | display        | 6                   |
      | popupwidth     | 620                 |
      | popupheight    | 450                 |
    When I am on the "Zatuk history" "zatuk activity" page logged in as student1
    Then the "View" completion condition of "Zatuk history" is displayed as "done"

  @javascript
  Scenario: Use manual completion with automatic zatuk as teacher
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 1                   |
      | completionview | 1                   |
      | display        | 0                   |
    When I am on the "Zatuk history" "zatuk activity" page logged in as teacher1
    Then the manual completion button for "Zatuk history" should be disabled

  @javascript
  Scenario: Use manual completion with automatic zatuk as student
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 1                   |
      | completionview | 1                   |
      | display        | 0                   |
    When I am on the "Course 1" course page logged in as student1
    Then the manual completion button of "Zatuk history" is displayed as "Mark as done"
    And I toggle the manual completion state of "Zatuk history"
    And the manual completion button of "Zatuk history" is displayed as "Done"

  @javascript
  Scenario Outline: The manual completion button will be shown on the course page for Open, In pop-up and New window display mode if the Show activity completion conditions is set to No as teacher
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 1                   |
      | completionview | 1                   |
      | display        | <display>           |
      | popupwidth     | 620                 |
      | popupheight    | 450                 |
    When I am on the "Course 1" course page logged in as teacher1
    Then the manual completion button for "Zatuk history" should exist
    And the manual completion button for "Zatuk history" should be disabled

    Examples:
      | display | description |
      | 0       | Auto        |
      | 6       | Popup       |
      | 3       | New         |

  @javascript
  Scenario Outline: The manual completion button will be shown on the course page for Open, In pop-up and New window display mode if the Show activity completion conditions is set to No as student
    Given the following "activity" exists:
      | activity       | zatuk               |
      | course         | C1                  |
      | name           | Zatuk history       |
      | intro          | Zatuk description   |
      | externalurl    | https://moodle.org/ |
      | completion     | 1                   |
      | completionview | 1                   |
      | display        | <display>           |
      | popupwidth     | 620                 |
      | popupheight    | 450                 |
    When I am on the "Course 1" course page logged in as student1
    Then the manual completion button for "Zatuk history" should exist
    And the manual completion button of "Zatuk history" is displayed as "Mark as done"
    And I toggle the manual completion state of "Zatuk history"
    And the manual completion button of "Zatuk history" is displayed as "Done"

    Examples:
      | display | description |
      | 0       | Auto        |
      | 6       | Popup       |
      | 3       | New         |
