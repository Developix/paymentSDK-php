<?php
// # iDEAL payment transaction

// This example displays the usage payments for payment method iDEAL.

// ## Required objects

// To include the necessary files, we use the composer for PSR-4 autoloading.
require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../../inc/common.php';
require __DIR__ . '/../../configuration/globalconfig.php';

require __DIR__ . '/../../inc/header.php';

use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Entity\IdealBic;
use Wirecard\PaymentSdk\Entity\Redirect;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\InteractionResponse;
use Wirecard\PaymentSdk\Transaction\IdealTransaction;
use Wirecard\PaymentSdk\TransactionService;


// ### Transaction related objects

// Use the amount object as amount which has to be paid by the consumer.
$amount = new Amount(12.59, 'EUR');

// Set redirect URLs for success, cancel and failure.
// From payment page you will be redirected to:
// Success URL when the payment is approved.
// Cancel URL when the user cancels the transaction on payment page.
// Failure URL when payment is not approved or the data are missing or incorrect
$redirectUrls = new Redirect(
    getUrl('return.php?status=success'),
    getUrl('return.php?status=cancel'),
    getUrl('return.php?status=failure')
);

// As soon as the transaction status changes, a server-to-server notification will get delivered to this URL.
$notificationUrl = getUrl('notify.php');

$accountHolder = new AccountHolder();
// The account holder last name is required.
$accountHolder->setLastName('Doe');
// The account holders first name is optional.
// For complete list of all fields please visit https://doc.wirecard.com/RestApi_Fields.html
$accountHolder->setFirstName('Jane');

// ### Transaction

// The IdealTransaction object holds all transaction relevant data for the payment process.
// The required fields are: amount, descriptor, success and cancel redirect URL-s
$transaction = new IdealTransaction();

// ### Mandatory fields

$transaction->setRedirect($redirectUrls);
$transaction->setAmount($amount);
$transaction->setBic(IdealBic::INGBNL2A);

// ### Optional fields
// For the full list of fields see: https://doc.wirecard.com/RestApi_Fields.html
$transaction->setNotificationUrl($notificationUrl);
$transaction->setAccountHolder($accountHolder);
$transaction->setDescriptor('customer-statement');

// ### Transaction Service

// The service is used to execute the payment operation itself. A response object is returned.
$transactionService = new TransactionService($config);
$response = $transactionService->pay($transaction);


// ## Response handling

// The response of the service must be handled depending on it's class
// In case of an `InteractionResponse`, a browser interaction by the consumer is required
// in order to continue the payment process. In this example we proceed with a header redirect
// to the given _redirectUrl_. IFrame integration using this URL is also possible.
if ($response instanceof InteractionResponse) {
    die("<meta http-equiv='refresh' content='0;url={$response->getRedirectUrl()}'>");

// The failure state is represented by a FailureResponse object.
// In this case the returned errors should be stored in your system.
} elseif ($response instanceof FailureResponse) {
// In our example we iterate over all errors and echo them out. You should display them as
// error, warning or information based on the given severity.
    foreach ($response->getStatusCollection() as $status) {
        /**
         * @var $status \Wirecard\PaymentSdk\Entity\Status
         */
        $severity = ucfirst($status->getSeverity());
        $code = $status->getCode();
        $description = $status->getDescription();
        echo sprintf('%s with code %s and message "%s" occured.<br>', $severity, $code, $description);
    }
}

require __DIR__ . '/../../inc/footer.php';
