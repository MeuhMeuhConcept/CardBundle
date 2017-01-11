Feature: CardProcessor

    Scenario: Test Create Card
        Given I create a new card
        Then I should see the reason phrase "request_is_ok" and the status code "OK"
        And the card contains 1 item with status "c"
        And the card contains 0 item with status "v"
        And the card contains 0 item with status "a"
        And the card contains 0 item with status "d"
        And the card contains 0 item with status "x"
        Given I take the item with "status" equal to "c"
        And I set "title" of current item to "tintin"

        Given I valide the current item
        Then I should see the reason phrase "request_is_ok" and the status code "OK"
        And the card contains 0 item with status "c"
        And the card contains 1 item with status "v"
        And the card contains 0 item with status "a"
        And the card contains 0 item with status "d"
        And the card contains 0 item with status "x"
        And The "title" of current item is equals to "tintin"

        Given I create a draft version of current item
        Then I should see the reason phrase "request_is_ok" and the status code "OK"
        And the card contains 0 item with status "c"
        And the card contains 1 item with status "v"
        And the card contains 0 item with status "a"
        And the card contains 1 item with status "d"
        And the card contains 0 item with status "x"

        Given I create a draft version of current item
        Then I should see the reason phrase "draft_already_exist" and the status code "OK"
        And the card contains 0 item with status "c"
        And the card contains 1 item with status "v"
        And the card contains 0 item with status "a"
        And the card contains 1 item with status "d"
        And the card contains 0 item with status "x"
        Given I take the item with "status" equal to "d"
        And I set "title" of current item to "toto"

        Given I valide the current item
        Then I should see the reason phrase "request_is_ok" and the status code "OK"
        And the card contains 0 item with status "c"
        And the card contains 1 item with status "v"
        And the card contains 1 item with status "a"
        And the card contains 0 item with status "d"
        And the card contains 0 item with status "x"
        Given I take the item with "status" equal to "v"
        Then The "title" of current item is equals to "toto"
        Given I take the item with "status" equal to "a"
        Then The "title" of current item is equals to "tintin"

        Given I take the item with "status" equal to "v"
        And I create a draft version of current item
        Then I should see the reason phrase "request_is_ok" and the status code "OK"
        And the card contains 0 item with status "c"
        And the card contains 1 item with status "v"
        And the card contains 1 item with status "a"
        And the card contains 1 item with status "d"
        And the card contains 0 item with status "x"

        Given I take the item with "status" equal to "d"
        And I delete draft
        Then I should see the reason phrase "request_is_ok" and the status code "OK"
        And the card contains 0 item with status "c"
        And the card contains 1 item with status "v"
        And the card contains 1 item with status "a"
        And the card contains 0 item with status "d"
        And the card contains 1 item with status "x"

        Given I take the item with "status" equal to "v"
        And I archived the card
        Then I should see the reason phrase "request_is_ok" and the status code "OK"
        And the card contains 0 item with status "c"
        And the card contains 0 item with status "v"
        And the card contains 2 item with status "a"
        And the card contains 0 item with status "d"
        And the card contains 1 item with status "x"
