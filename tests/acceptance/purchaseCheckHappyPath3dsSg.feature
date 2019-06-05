Feature: checkCreditCard3DSFunctionalityHappyPathSg
  As a user
  I want to make a purchase with a Credit Card 3DS
  And to see that transaction was successful
  And to be able to cancel the transaction

  Background:
    Given I am on "Create Credit Card UI Page" page
    When I fill fields with "Valid Credit Card Data"
    And I enter "70" in field "Amount"
    And I choose "SGD" in field "Currency"
    And I click "Save"

  @TEST-SG @SECURE-TEST-SG
  Scenario: try purchaseCheck
    Given I am redirected to "Credit Card Reserve Page" page
    Then I see text "Reservation successfully completed."
    And I see text "Transaction ID"

  @TEST-SG @SECURE-TEST-SG
  Scenario: try voidPurchaseCheck
    Given I am redirected to "Credit Card Reserve Page" page
    Then I see text "Reservation successfully completed."
    And I see text "Transaction ID"
    And I note the "Transaction Identification"
    And I am on "Credit Card Cancel Page" page
    And I enter "Noted Transaction Identification" in field "Transaction ID to be refunded"
    And I click "Refund"
    Then I see text "Payment successfully cancelled."
    And I see text "Transaction ID"
