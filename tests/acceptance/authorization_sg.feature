Feature: check_credit_card_authorization
  As a user
  I want to make a purchase with a Credit Card 3DS
  And to see that transaction was successful
  And to be able to cancel the transaction

  Background:
    Given I am on "Create Credit Card UI Authorization Page" page
    When I fill fields with "Valid Credit Card Data"
    And I enter "70" in field "Amount"
    And I choose "SGD" in field "Currency"
    And I click "Save"

  @SECURE-TEST-SG @TEST-SG
  Scenario: try authorization
    Given I am redirected to "Credit Card Reserve Page" page
    Then I see text "Reservation successfully completed."
    And I see text "Transaction ID"

  @SECURE-TEST-SG @TEST-SG
  Scenario: try capture
    Given I am redirected to "Credit Card Reserve Page" page
    And I see text "Transaction ID"
    And I note the "Transaction Identification"
    When I am on "Create Credit Card Pay Based On Reserve" page
    And I enter "Noted Transaction Identification" in field "Reserved transaction ID"
    And I enter "70" in field "Amount"
    And I click "Pay"
    Then I see text "Payment successfully completed."
    And I see text "Transaction ID"

  @SECURE-TEST-SG @TEST-SG
  Scenario: try refund
    Given I am redirected to "Credit Card Reserve Page" page
    And I see text "Transaction ID"
    And I note the "Transaction Identification"
    When I am on "Create Credit Card Pay Based On Reserve" page
    And I enter "Noted Transaction Identification" in field "Reserved transaction ID"
    And I enter "70" in field "Amount"
    And I click "Pay"
    And I see text "Payment successfully completed."
    And I see text "Transaction ID"
    And I note the "Transaction Identification"
    Then I am on "Credit Card Cancel Page" page
    And I enter "Noted Transaction Identification" in field "Transaction ID to be refunded"
    And I click "Refund"
    And I see text "Payment successfully cancelled."
    And I see text "Transaction ID"

  @SECURE-TEST-SG @TEST-SG
  Scenario: try cancel
    Given I am redirected to "Credit Card Reserve Page" page
    And I see text "Transaction ID"
    And I note the "Transaction Identification"
    When I am on "Credit Card Cancel Page" page
    And I enter "Noted Transaction Identification" in field "Transaction ID to be refunded"
    And I click "Refund"
    Then I see text "Payment successfully cancelled."
    And I see text "Transaction ID"